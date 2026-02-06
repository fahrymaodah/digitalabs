<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name',
        'title',
        'avatar',
        'content',
        'rating',
        'is_published',
        'order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_published' => 'boolean',
        'order' => 'integer',
    ];

    // ==================== SCOPES ====================

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // ==================== ACCESSORS ====================

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            // If already a full URL (external image)
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            // If local storage path
            return asset('storage/' . $this->avatar);
        }

        // Fallback to UI Avatars
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=f97316&color=fff&size=200';
    }
}
