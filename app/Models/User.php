<?php

namespace App\Models;

use App\Traits\HasUuid;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuid;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'province_id',
        'city_id',
        'district_id',
        'is_admin',
        'google_id',
        'provider',
        'uuid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Determine if user can access Filament panel
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->is_admin;
        }

        return true; // User panel accessible by all authenticated users
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * User's province
     */
    public function province(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * User's city
     */
    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * User's district
     */
    public function district(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * User's purchased courses (through user_courses)
     */
    public function userCourses(): HasMany
    {
        return $this->hasMany(UserCourse::class);
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

    /**
     * Courses owned by user
     */
    public function courses(): HasManyThrough
    {
        return $this->hasManyThrough(
            Course::class,
            UserCourse::class,
            'user_id',
            'id',
            'id',
            'course_id'
        );
    }

    /**
     * User's orders
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * User's lesson progress
     */
    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * User's affiliate account (if any)
     */
    public function affiliate(): HasOne
    {
        return $this->hasOne(Affiliate::class);
    }

    /**
     * User's course reviews
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(CourseReview::class);
    }

    /**
     * User's articles (if author)
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if user has purchased a course
     */
    public function hasPurchased(Course $course): bool
    {
        return $this->userCourses()
            ->where('course_id', $course->id)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    /**
     * Check if user is an approved affiliate
     */
    public function isAffiliate(): bool
    {
        return $this->affiliate?->status === 'approved';
    }

    /**
     * Get course progress percentage
     */
    public function getCourseProgress(Course $course): float
    {
        $totalLessons = $course->total_lessons;
        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = $this->lessonProgress()
            ->whereHas('lesson.section', fn ($q) => $q->where('course_id', $course->id))
            ->where('is_completed', true)
            ->count();

        return round(($completedLessons / $totalLessons) * 100, 2);
    }
}
