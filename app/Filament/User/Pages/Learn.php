<?php

namespace App\Filament\User\Pages;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\UserCourse;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

class Learn extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-play-circle';
    protected static ?string $navigationLabel = 'Learn';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'learn/{courseSlug}';
    protected string $view = 'filament.user.pages.learn';

    public string $courseSlug = '';
    
    #[Url]
    public ?int $lessonId = null;

    public ?Course $course = null;
    public ?Lesson $currentLesson = null;
    public $completedLessonIds = [];
    public bool $userOwnsCourse = false;

    public function mount(): void
    {
        $this->loadCourseData();
    }
    
    /**
     * Computed property for topics - ALWAYS returns correct filtered data
     * This ensures sidebar NEVER shows paid lessons for free preview users
     */
    #[Computed]
    public function topics()
    {
        if (!$this->course) {
            return collect([]);
        }
        
        // If user owns course, return all lessons
        if ($this->userOwnsCourse) {
            return $this->course->topics()->with(['lessons' => function ($query) {
                $query->orderBy('order');
            }])->orderBy('order')->get();
        }
        
        // Free preview - only return free lessons
        return $this->course->topics()->with(['lessons' => function ($query) {
            $query->where('is_free', true)->orderBy('order');
        }])->orderBy('order')->get();
    }

    #[On('updated-course-slug')]
    public function loadCourseData(): void
    {
        if (!$this->courseSlug) {
            return;
        }

        $user = Auth::user();

        // Get course by slug
        $this->course = Course::where('slug', $this->courseSlug)->firstOrFail();

        // Check if user owns this course
        $hasAccess = UserCourse::where('user_id', $user->id)
            ->where('course_id', $this->course->id)
            ->exists();

        $this->userOwnsCourse = $hasAccess;

        // If user doesn't own the course, check if accessing free preview lesson
        if (!$hasAccess) {
            // If lessonId is provided, check if it's a free lesson
            if ($this->lessonId) {
                $lesson = Lesson::find($this->lessonId);
                if (!$lesson || !$lesson->is_free) {
                    abort(403, 'You do not have access to this course. Please purchase the course to continue.');
                }
                // Allow access to this free lesson only
                $this->currentLesson = $lesson;
                // Free preview users don't track progress
                $this->completedLessonIds = [];
                return;
            } else {
                abort(403, 'You do not have access to this course. Please purchase the course to continue.');
            }
        }

        // Get completed lesson IDs for course owners only
        $this->completedLessonIds = LessonProgress::where('user_id', $user->id)
            ->whereHas('lesson.topic', function ($q) {
                $q->where('course_id', $this->course->id);
            })
            ->where('is_completed', true)
            ->pluck('lesson_id')
            ->toArray();

        // Set current lesson
        if ($this->lessonId) {
            $this->currentLesson = Lesson::find($this->lessonId);
        } else {
            // Find first uncompleted lesson or first lesson
            $topics = $this->topics; // Use computed property
            foreach ($topics as $topic) {
                foreach ($topic->lessons as $lesson) {
                    if (!in_array($lesson->id, $this->completedLessonIds)) {
                        $this->currentLesson = $lesson;
                        break 2;
                    }
                }
            }
            // If all completed, show first lesson
            if (!$this->currentLesson && $topics->count() > 0) {
                $this->currentLesson = $topics->first()->lessons->first();
            }
        }
    }

    public function selectLesson(int $lessonId): void
    {
        $lesson = Lesson::find($lessonId);
        
        if (!$lesson) {
            $this->dispatch('error', message: 'Lesson not found.');
            return;
        }
        
        // If user doesn't own course, only allow free lessons
        if (!$this->userOwnsCourse && !$lesson->is_free) {
            $this->dispatch('error', message: 'This lesson is only available after purchasing the course.');
            return;
        }
        
        $this->currentLesson = $lesson;
        $this->lessonId = $lessonId;
        
        // Topics are now computed property - no need to set manually
        // It will automatically filter based on userOwnsCourse
        
        // Always dispatch event so JavaScript can handle video/no-video transition
        $videoId = $this->getYoutubeVideoId();
        $this->dispatch('lesson-changed', lessonId: $lessonId, videoId: $videoId, startSeconds: $this->getCurrentLessonWatchedSeconds());
    }

    /**
     * Save video progress (called from JavaScript)
     */
    public function saveProgress(int $watchedSeconds): void
    {
        if (!$this->currentLesson) return;
        
        // Only save progress if user owns the course
        if (!$this->userOwnsCourse) return;

        $user = Auth::user();

        // Don't overwrite progress if lesson is already completed
        // Completed lessons should keep their full duration as watched_seconds
        $existing = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $this->currentLesson->id)
            ->first();
        
        if ($existing && $existing->is_completed) {
            // Lesson already completed, don't overwrite watched_seconds
            return;
        }

        LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $this->currentLesson->id,
            ],
            [
                'watched_seconds' => $watchedSeconds,
            ]
        );
    }

    /**
     * Save progress for a specific lesson (used for auto-save before switching)
     * This allows saving progress of the PREVIOUS lesson before Livewire state updates
     */
    public function saveProgressWithLesson(int $lessonId, int $watchedSeconds): void
    {
        // Only save progress if user owns the course
        if (!$this->userOwnsCourse) return;
        
        $user = Auth::user();

        // Don't overwrite progress if lesson is already completed
        // Completed lessons should keep their full duration as watched_seconds
        $existing = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lessonId)
            ->first();
        
        if ($existing && $existing->is_completed) {
            // Lesson already completed, don't overwrite watched_seconds
            return;
        }

        LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lessonId,
            ],
            [
                'watched_seconds' => $watchedSeconds,
            ]
        );
    }

    public function markAsComplete(): void
    {
        if (!$this->currentLesson) return;
        
        // Only allow marking complete if user owns the course
        if (!$this->userOwnsCourse) return;

        $user = Auth::user();
        
        // Store current lesson info before advancing
        $completedLessonId = $this->currentLesson->id;
        $completedLessonDuration = $this->currentLesson->duration ?? 0;
        
        // Check if lesson was ALREADY completed before this call
        // If already completed, user is just rewatching - don't auto-advance
        $wasAlreadyCompleted = in_array($completedLessonId, $this->completedLessonIds);

        // When marking as complete, set watched_seconds to full lesson duration
        // This ensures progress shows 100% even if user didn't watch till the end
        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $completedLessonId,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
                'watched_seconds' => $completedLessonDuration,
            ]
        );
        
        // Force save to database immediately
        $progress->save();
        
        // Log for debugging
        \Log::info('Marked lesson as complete', [
            'lesson_id' => $completedLessonId,
            'duration' => $completedLessonDuration,
            'watched_seconds_saved' => $progress->watched_seconds,
            'is_completed' => $progress->is_completed,
            'was_already_completed' => $wasAlreadyCompleted,
        ]);

        // Add to completed list if not already there
        if (!$wasAlreadyCompleted) {
            $this->completedLessonIds[] = $completedLessonId;
        }
        
        // Refresh computed properties to get latest data
        unset($this->topics);

        // IMPORTANT: Only auto-advance if this was a NEW completion
        // If user is rewatching a completed video, don't auto-advance
        if ($wasAlreadyCompleted) {
            \Log::info('Lesson was already completed - no auto-advance (rewatch mode)');
            return;
        }

        // Auto-advance to next lesson
        $this->goToNextLesson();
        
        // Dispatch event to JavaScript to switch player to next lesson
        // IMPORTANT: Always dispatch, even if no video (videoId can be null)
        // JavaScript needs to know about lesson changes to clear old player
        $videoId = $this->getYoutubeVideoId();
        $this->dispatch('lesson-changed', lessonId: $this->currentLesson->id, videoId: $videoId, startSeconds: $this->getCurrentLessonWatchedSeconds());
    }

    public function goToNextLesson(): void
    {
        // Only allow navigation if user owns the course
        if (!$this->userOwnsCourse) return;
        
        $foundCurrent = false;
        foreach ($this->topics as $topic) {
            foreach ($topic->lessons as $lesson) {
                if ($foundCurrent) {
                    $this->currentLesson = $lesson;
                    $this->lessonId = $lesson->id;
                    
                    // Dispatch event to update video player
                    $videoId = $this->getYoutubeVideoId();
                    $this->dispatch('lesson-changed', lessonId: $this->currentLesson->id, videoId: $videoId, startSeconds: $this->getCurrentLessonWatchedSeconds());
                    return;
                }
                if ($this->currentLesson && $lesson->id === $this->currentLesson->id) {
                    $foundCurrent = true;
                }
            }
        }
    }

    public function goToPreviousLesson(): void
    {
        // Only allow navigation if user owns the course
        if (!$this->userOwnsCourse) return;
        
        $previousLesson = null;
        foreach ($this->topics as $topic) {
            foreach ($topic->lessons as $lesson) {
                if ($this->currentLesson && $lesson->id === $this->currentLesson->id) {
                    if ($previousLesson) {
                        $this->currentLesson = $previousLesson;
                        $this->lessonId = $previousLesson->id;
                        
                        // Dispatch event to update video player
                        $videoId = $this->getYoutubeVideoId();
                        $this->dispatch('lesson-changed', lessonId: $this->currentLesson->id, videoId: $videoId, startSeconds: $this->getCurrentLessonWatchedSeconds());
                    }
                    return;
                }
                $previousLesson = $lesson;
            }
        }
    }

    public function getTitle(): string
    {
        return $this->course?->title ?? 'Learn';
    }

    public function getYoutubeEmbedUrl(): ?string
    {
        if (!$this->currentLesson || !$this->currentLesson->youtube_url) {
            return null;
        }

        $url = $this->currentLesson->youtube_url;
        
        // Extract video ID from various YouTube URL formats
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        
        if (isset($matches[1])) {
            $videoId = $matches[1];
            
            // Get last watched position
            $user = Auth::user();
            $progress = LessonProgress::where('user_id', $user->id)
                ->where('lesson_id', $this->currentLesson->id)
                ->first();
            
            $startSeconds = $progress?->watched_seconds ?? 0;
            
            // rel=0: Disable related videos from other channels
            // start: Resume dari detik terakhir
            return 'https://www.youtube.com/embed/' . $videoId . '?rel=0&start=' . $startSeconds;
        }

        return $url;
    }

    /**
     * Get YouTube video ID for IFrame API
     */
    public function getYoutubeVideoId(): ?string
    {
        if (!$this->currentLesson || !$this->currentLesson->youtube_url) {
            return null;
        }

        $url = $this->currentLesson->youtube_url;
        
        // Extract video ID from various YouTube URL formats
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        
        return $matches[1] ?? null;
    }

    /**
     * Get current lesson watched seconds for JavaScript
     */
    public function getCurrentLessonWatchedSeconds(): int
    {
        if (!$this->currentLesson) {
            return 0;
        }

        // If lesson is already completed, always start from 0 for rewatch
        if (in_array($this->currentLesson->id, $this->completedLessonIds)) {
            return 0;
        }

        return LessonProgress::where('user_id', Auth::id())
            ->where('lesson_id', $this->currentLesson->id)
            ->value('watched_seconds') ?? 0;
    }

    /**
     * Get duration in format MM:SS or HH:MM:SS
     */
    public static function formatDuration(int $seconds): string
    {
        $hours = (int)($seconds / 3600);
        $minutes = (int)(($seconds % 3600) / 60);
        $secs = $seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
        }
        
        return sprintf('%d:%02d', $minutes, $secs);
    }

    /**
     * Get total duration for course
     */
    public function getCourseTotalDuration(): int
    {
        if (!$this->course) {
            return 0;
        }
        
        return $this->course->lessons()
            ->sum('duration') ?? 0;
    }

    /**
     * Get watched duration for course
     */
    public function getCourseWatchedDuration(): int
    {
        if (!$this->course) {
            return 0;
        }
        
        return LessonProgress::where('user_id', Auth::id())
            ->whereHas('lesson.topic', function ($q) {
                $q->where('course_id', $this->course->id);
            })
            ->sum('watched_seconds') ?? 0;
    }

    /**
     * Get total duration for topic
     */
    public function getTopicTotalDuration($topic): int
    {
        return $topic->lessons()->sum('duration') ?? 0;
    }

    /**
     * Get watched duration for topic
     */
    public function getTopicWatchedDuration($topic): int
    {
        return LessonProgress::where('user_id', Auth::id())
            ->whereHas('lesson', function ($q) use ($topic) {
                $q->where('topic_id', $topic->id);
            })
            ->sum('watched_seconds') ?? 0;
    }

    /**
     * Get watched seconds for lesson
     */
    public function getLessonWatchedSeconds($lesson): int
    {
        return LessonProgress::where('user_id', Auth::id())
            ->where('lesson_id', $lesson->id)
            ->value('watched_seconds') ?? 0;
    }
}
