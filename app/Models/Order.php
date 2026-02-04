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

    // ==================== METHODS ====================

    /**
     * Mark order as paid
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Grant course access to user
        foreach ($this->items as $item) {
            UserCourse::updateOrCreate(
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
        }

        // Create affiliate commission if applicable
        if ($this->affiliate_id) {
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
        }
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
