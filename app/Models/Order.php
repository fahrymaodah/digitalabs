<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasUuid;

    protected $fillable = [
        'order_number',
        'user_id',
        'affiliate_id',
        'coupon_id',
        'subtotal',
        'discount',
        'payment_fee',
        'total',
        'payment_method',
        'status',
        'duitku_reference',
        'duitku_payment_url',
        'duitku_response',
        'paid_at',
        'expired_at',
        'uuid',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'payment_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'duitku_response' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function commission(): HasMany
    {
        return $this->hasMany(AffiliateCommission::class);
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    // ==================== ACCESSORS ====================

    public function getIsPaidAttribute(): bool
    {
        return $this->status === 'paid';
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->status === 'expired';
    }

    // ==================== ACCESSORS ====================

    /**
     * Get the first course from order items (for email templates compatibility)
     * Since most orders have single item, this provides backward compatibility
     */
    public function getCourseAttribute()
    {
        return $this->items->first()?->course;
    }

    /**
     * Get the original price before discount (subtotal)
     */
    public function getOriginalPriceAttribute()
    {
        return $this->subtotal ?? $this->items->sum('price');
    }

    /**
     * Get the discount amount (for email templates)
     */
    public function getDiscountAmountAttribute()
    {
        return $this->discount ?? 0;
    }

    /**
     * Get the total price after discount (for email templates)
     */
    public function getTotalPriceAttribute()
    {
        return $this->total ?? 0;
    }

    // ==================== METHODS ====================

    /**
     * Mark order as paid
     */
    public function markAsPaid(): void
    {
        \Log::info('markAsPaid called', ['order_id' => $this->id]);
        
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        \Log::info('markAsPaid: Status updated, now granting course access', [
            'order_id' => $this->id,
            'items_count' => $this->items->count()
        ]);

        // Grant course access to user
        foreach ($this->items as $item) {
            \Log::info('markAsPaid: Processing item', [
                'order_id' => $this->id,
                'course_id' => $item->course_id,
                'user_id' => $this->user_id
            ]);
            
            try {
                $userCourse = UserCourse::updateOrCreate(
                    [
                        'user_id' => $this->user_id,
                        'course_id' => $item->course_id,
                    ],
                    [
                        'order_id' => $this->id,
                        'purchased_at' => now(),
                        'expires_at' => $item->course->access_type === 'limited'
                            ? now()->addDays($item->course->access_days)
                            : null,
                    ]
                );
                
                \Log::info('markAsPaid: UserCourse created/updated', [
                    'user_course_id' => $userCourse->id,
                    'course_id' => $item->course_id,
                    'user_id' => $this->user_id
                ]);
            } catch (\Exception $e) {
                \Log::error('markAsPaid: Failed to create UserCourse', [
                    'order_id' => $this->id,
                    'course_id' => $item->course_id,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        }

        // Create affiliate commission if applicable
        if ($this->affiliate_id) {
            \Log::info('markAsPaid: Creating affiliate commission', ['affiliate_id' => $this->affiliate_id]);
            
            $affiliate = $this->affiliate;
            $commissionAmount = $this->total * ($affiliate->commission_rate / 100);

            AffiliateCommission::create([
                'affiliate_id' => $affiliate->id,
                'order_id' => $this->id,
                'order_amount' => $this->total,
                'commission_rate' => $affiliate->commission_rate,
                'commission_amount' => $commissionAmount,
                'status' => 'pending',
            ]);

            $affiliate->increment('pending_earnings', $commissionAmount);
            $affiliate->increment('total_earnings', $commissionAmount);
            
            \Log::info('markAsPaid: Affiliate commission created', ['commission_amount' => $commissionAmount]);
        }
        
        \Log::info('markAsPaid completed successfully', ['order_id' => $this->id]);
    }

    // ==================== BOOT ====================

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(10));
            }
        });
    }
}
