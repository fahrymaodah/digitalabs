<?php

namespace App\Filament\User\Pages;

use App\Models\Course;
use App\Models\LessonProgress;
use App\Models\Order;
use App\Models\UserCourse;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Dashboard';
    protected static ?string $slug = '/';
    protected string $view = 'filament.user.pages.dashboard';

    public function getViewData(): array
    {
        $user = Auth::user();

        // Get user's courses with progress calculation
        $userCourses = UserCourse::where('user_id', $user->id)
            ->with(['course.topics.lessons'])
            ->get();

        // Calculate progress for each course
        $coursesWithProgress = $userCourses->map(function ($userCourse) use ($user) {
            $course = $userCourse->course;
            
            // Get all lessons for this course
            $lessonIds = $course->topics->flatMap(function ($topic) {
                return $topic->lessons->pluck('id');
            })->toArray();
            
            // Calculate total duration of the course
            $totalDuration = $course->topics->flatMap(function ($topic) {
                return $topic->lessons;
            })->sum('duration') ?: 0;
            
            // Calculate total watched seconds
            $watchedSeconds = LessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_id', $lessonIds)
                ->sum('watched_seconds');
            
            // Count completed lessons
            $completedCount = LessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_id', $lessonIds)
                ->where('is_completed', true)
                ->count();
            
            $totalLessons = count($lessonIds);
            
            // Calculate progress percentage based on watched time
            $progress = $totalDuration > 0 
                ? min(100, round(($watchedSeconds / $totalDuration) * 100)) 
                : 0;
            
            return [
                'userCourse' => $userCourse,
                'course' => $course,
                'progress' => $progress,
                'watchedSeconds' => $watchedSeconds,
                'totalDuration' => $totalDuration,
                'completedLessons' => $completedCount,
                'totalLessons' => $totalLessons,
            ];
        });

        // Calculate overall progress
        $totalDuration = $coursesWithProgress->sum('totalDuration');
        $totalWatched = $coursesWithProgress->sum('watchedSeconds');
        $totalLessons = $coursesWithProgress->sum('totalLessons');
        $completedLessons = $coursesWithProgress->sum('completedLessons');
        
        $overallProgress = $totalDuration > 0 
            ? min(100, round(($totalWatched / $totalDuration) * 100)) 
            : 0;

        // Recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'user' => $user,
            'coursesCount' => $userCourses->count(),
            'completedLessons' => $completedLessons,
            'totalLessons' => $totalLessons,
            'overallProgress' => $overallProgress,
            'recentOrders' => $recentOrders,
            'coursesWithProgress' => $coursesWithProgress,
        ];
    }
    
    /**
     * Format seconds to human readable duration
     */
    public static function formatDuration(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
        }
        return sprintf('%d:%02d', $minutes, $secs);
    }
}
