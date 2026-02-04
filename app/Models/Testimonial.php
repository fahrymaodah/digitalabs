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
}
