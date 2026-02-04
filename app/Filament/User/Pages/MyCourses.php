<?php

namespace App\Filament\User\Pages;

use App\Models\LessonProgress;
use App\Models\UserCourse;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class MyCourses extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'My Courses';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'My Courses';
    protected string $view = 'filament.user.pages.my-courses';

    public function getViewData(): array
    {
        $user = Auth::user();

        $userCourses = UserCourse::where('user_id', $user->id)
            ->with(['course.topics.lessons', 'course.category'])
            ->get()
            ->map(function ($userCourse) use ($user) {
                $course = $userCourse->course;
                
                // Get all lessons for this course
                $lessonIds = $course->topics->flatMap(function ($topic) {
                    return $topic->lessons->pluck('id');
                })->toArray();
                
                $totalLessons = count($lessonIds);
                
                // Calculate total duration of the course
                $totalDuration = $course->topics->flatMap(function ($topic) {
                    return $topic->lessons;
                })->sum('duration') ?: 0;
                
                // Calculate total watched seconds
                $watchedSeconds = LessonProgress::where('user_id', $user->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->sum('watched_seconds');
                
                // Count completed lessons
                $completedLessons = LessonProgress::where('user_id', $user->id)
                    ->whereIn('lesson_id', $lessonIds)
                    ->where('is_completed', true)
                    ->count();

                // Calculate progress percentage based on watched time
                $progress = $totalDuration > 0 
                    ? min(100, round(($watchedSeconds / $totalDuration) * 100)) 
                    : 0;

                // Get next lesson to continue
                $nextLesson = null;
                foreach ($course->topics as $topic) {
                    foreach ($topic->lessons as $lesson) {
                        $isCompleted = LessonProgress::where('user_id', $user->id)
                            ->where('lesson_id', $lesson->id)
                            ->where('is_completed', true)
                            ->exists();
                        
                        if (!$isCompleted) {
                            $nextLesson = $lesson;
                            break 2;
                        }
                    }
                }

                return [
                    'userCourse' => $userCourse,
                    'course' => $course,
                    'totalLessons' => $totalLessons,
                    'completedLessons' => $completedLessons,
                    'progress' => $progress,
                    'watchedSeconds' => $watchedSeconds,
                    'totalDuration' => $totalDuration,
                    'nextLesson' => $nextLesson,
                    'purchasedAt' => $userCourse->purchased_at,
                ];
            });

        return [
            'courses' => $userCourses,
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
