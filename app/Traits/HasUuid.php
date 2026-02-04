<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait HasUuid
 * 
 * Automatically generates UUID for models on creation.
 * Use this trait on models that need UUID for public-facing routes.
 */
trait HasUuid
{
    /**
     * Boot the HasUuid trait.
     */
    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * Get the route key name for Laravel route model binding.
     * This allows using UUID in routes instead of ID.
     * 
     * Note: Only use this for public-facing routes (User Panel, API).
     * Admin Panel should continue using ID for simplicity.
     */
    public function getRouteKeyName(): string
    {
        // Check if we're in admin panel context - use ID
        // For other contexts (user panel, api) - use UUID
        if ($this->shouldUseIdForRouteKey()) {
            return 'id';
        }

        return 'uuid';
    }

    /**
     * Determine if we should use ID instead of UUID for route key.
     * Override this method in your model if needed.
     */
    protected function shouldUseIdForRouteKey(): bool
    {
        // Check if current request is from admin panel
        $request = request();
        
        if ($request) {
            $path = $request->path();
            // Admin panel uses /admin prefix
            if (str_starts_with($path, 'admin')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find a model by its UUID.
     */
    public static function findByUuid(string $uuid): ?static
    {
        return static::where('uuid', $uuid)->first();
    }

    /**
     * Find a model by its UUID or fail.
     */
    public static function findByUuidOrFail(string $uuid): static
    {
        return static::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Scope to find by UUID.
     */
    public function scopeWhereUuid($query, string $uuid)
    {
        return $query->where('uuid', $uuid);
    }
}
