<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'content',
        'thumbnail',
        'price',
        'sale_price',
        'preview_url',
        'total_duration',
        'total_lessons',
        'status',
        'access_type',
        'access_days',
        'order',
        'uuid',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'total_duration' => 'integer',
        'total_lessons' => 'integer',
        'access_days' => 'integer',
        'order' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class)->orderBy('order');
    }

    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, Topic::class);
    }

    public function userCourses(): HasMany
    {
        return $this->hasMany(UserCourse::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class);
    }

    public function publishedReviews(): HasMany
    {
        return $this->hasMany(CourseReview::class)->where('is_published', true);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ==================== SCOPES ====================

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // ==================== ACCESSORS ====================

    /**
     * Get effective price (sale_price if available, otherwise price)
     */
    public function getEffectivePriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Check if course is on sale
     */
    public function getIsOnSaleAttribute(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->is_on_sale) {
            return 0;
        }

        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    /**
     * Get formatted duration (e.g., "2h 30m")
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->total_duration / 3600);
        $minutes = floor(($this->total_duration % 3600) / 60);

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute(): float
    {
        return round($this->publishedReviews()->avg('rating') ?? 0, 1);
    }

    /**
     * Get total reviews count
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->publishedReviews()->count();
    }

    // ==================== METHODS ====================

    /**
     * Recalculate total duration and lessons from topics
     */
    public function recalculateTotals(): void
    {
        $this->total_duration = $this->topics()->sum('total_duration');
        $this->total_lessons = $this->topics()->sum('total_lessons');
        $this->save();
    }
}
