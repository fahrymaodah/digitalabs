<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'topic_id',
        'title',
        'description',
        'youtube_url',
        'duration',
        'order',
        'is_free',
    ];

    protected $casts = [
        'duration' => 'integer',
        'order' => 'integer',
        'is_free' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    // ==================== SCOPES ====================

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get formatted duration (e.g., "5:30")
     */
    public function getFormattedDurationAttribute(): string
    {
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Extract YouTube video ID from URL
     */
    public function getYoutubeIdAttribute(): ?string
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $this->youtube_url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Get secure embed URL
     */
    public function getEmbedUrlAttribute(): string
    {
        $videoId = $this->youtube_id;
        if (!$videoId) {
            return '';
        }

        $params = http_build_query([
            'modestbranding' => 1,
            'rel' => 0,
            'showinfo' => 0,
            'fs' => 1,
            'iv_load_policy' => 3,
            'playsinline' => 1,
        ]);

        return "https://www.youtube-nocookie.com/embed/{$videoId}?{$params}";
    }

    /**
     * Get course through topic
     */
    public function getCourseAttribute(): Course
    {
        return $this->topic->course;
    }

    // ==================== BOOT ====================

    protected static function booted(): void
    {
        // Recalculate topic totals when lesson is saved or deleted
        static::saved(function (Lesson $lesson) {
            $lesson->topic->recalculateTotals();
        });

        static::deleted(function (Lesson $lesson) {
            $lesson->topic->recalculateTotals();
        });
    }
}
