<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tutor extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'title',
        'bio',
        'avatar',
        'email',
        'phone',
        'website',
        'linkedin',
        'youtube',
        'instagram',
        'experience_years',
        'is_active',
        'order',
    ];

    protected $casts = [
        'experience_years' => 'integer',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Courses taught by this tutor
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_tutor')
            ->withPivot(['is_primary', 'order'])
            ->withTimestamps()
            ->orderByPivot('order');
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // ==================== ACCESSORS ====================

    /**
     * Get avatar URL with fallback
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=f97316&color=fff&size=200';
    }

    /**
     * Get social links as array
     */
    public function getSocialLinksAttribute(): array
    {
        $links = [];

        if ($this->website) {
            $links['website'] = $this->website;
        }
        if ($this->linkedin) {
            $links['linkedin'] = $this->linkedin;
        }
        if ($this->youtube) {
            $links['youtube'] = $this->youtube;
        }
        if ($this->instagram) {
            $links['instagram'] = $this->instagram;
        }

        return $links;
    }

    /**
     * Get total courses count
     */
    public function getCoursesCountAttribute(): int
    {
        return $this->courses()->count();
    }
}
