<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'total_duration',
        'total_lessons',
        'order',
    ];

    protected $casts = [
        'total_duration' => 'integer',
        'total_lessons' => 'integer',
        'order' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    // ==================== SCOPES ====================

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // ==================== ACCESSORS ====================

    /**
     * Get formatted duration
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

    // ==================== METHODS ====================

    /**
     * Recalculate total duration and lessons from lessons
     */
    public function recalculateTotals(): void
    {
        $this->total_duration = $this->lessons()->sum('duration');
        $this->total_lessons = $this->lessons()->count();
        $this->save();

        // Also update parent course
        $this->course->recalculateTotals();
    }
}
