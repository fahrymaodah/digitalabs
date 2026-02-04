<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'course_id',
        'original_price',
        'price',
        'discount',
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get the final price (price - discount)
     */
    public function getFinalPriceAttribute(): float
    {
        return $this->price - $this->discount;
    }

    /**
     * Get formatted original price
     */
    public function getFormattedOriginalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->original_price ?? $this->price, 0, ',', '.');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get the discount percentage
     */
    public function getDiscountPercentageAttribute(): ?float
    {
        if (!$this->original_price || $this->original_price <= 0) {
            return null;
        }
        
        $savedAmount = $this->original_price - $this->price;
        if ($savedAmount <= 0) {
            return 0;
        }
        
        return round(($savedAmount / $this->original_price) * 100, 1);
    }
}
