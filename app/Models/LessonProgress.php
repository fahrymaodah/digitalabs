<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model
{
    protected $table = 'lesson_progress';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'watched_seconds',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'watched_seconds' => 'integer',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    // ==================== METHODS ====================

    /**
     * Mark lesson as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    /**
     * Update watch progress
     */
    public function updateProgress(int $seconds): void
    {
        $this->update([
            'watched_seconds' => $seconds,
        ]);

        // Auto-complete if watched 90% or more
        $lesson = $this->lesson;
        if ($lesson->duration > 0 && ($seconds / $lesson->duration) >= 0.9) {
            $this->markAsCompleted();
        }
    }
}
