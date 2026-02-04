<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseReview extends Model
{
    use HasUuid;

    protected $fillable = [
        'course_id',
        'user_id',
        'rating',
        'review',
        'is_published',
        'published_at',
        'uuid',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ==================== SCOPES ====================

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeUnpublished($query)
    {
        return $query->where('is_published', false);
    }

    // ==================== METHODS ====================

    /**
     * Publish review
     */
    public function publish(): void
    {
        $this->update([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    /**
     * Unpublish review
     */
    public function unpublish(): void
    {
        $this->update([
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
