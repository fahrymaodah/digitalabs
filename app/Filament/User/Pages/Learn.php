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

class Learn extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-play-circle';
    protected static ?string $navigationLabel = 'Learn';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'learn/{courseSlug}/{lessonId?}';
    protected string $view = 'filament.user.pages.learn';

    #[Url]
    public string $courseSlug = '';
    
    #[Url]
    public ?int $lessonId = null;

    public ?Course $course = null;
    public ?Lesson $currentLesson = null;
    public $topics = [];
    public $completedLessonIds = [];

    public function mount(): void
    {
        $this->loadCourseData();
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

        if (!$hasAccess) {
            abort(403, 'You do not have access to this course.');
        }

        // Load topics with lessons
        $this->topics = $this->course->topics()->with('lessons')->orderBy('order')->get();

        // Get completed lesson IDs
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
            foreach ($this->topics as $topic) {
                foreach ($topic->lessons as $lesson) {
                    if (!in_array($lesson->id, $this->completedLessonIds)) {
                        $this->currentLesson = $lesson;
                        break 2;
                    }
                }
            }
            // If all completed, show first lesson
            if (!$this->currentLesson && $this->topics->count() > 0) {
                $this->currentLesson = $this->topics->first()->lessons->first();
            }
        }
    }

    public function selectLesson(int $lessonId): void
    {
        $this->currentLesson = Lesson::find($lessonId);
        $this->lessonId = $lessonId;
        
        // Only dispatch event if there's a video for this lesson
        $videoId = $this->getYoutubeVideoId();
        if ($videoId) {
            $this->dispatch('lesson-changed', lessonId: $lessonId, videoId: $videoId, startSeconds: $this->getCurrentLessonWatchedSeconds());
        }
    }

    /**
     * Save video progress (called from JavaScript)
     */
    public function saveProgress(int $watchedSeconds): void
    {
        if (!$this->currentLesson) return;

        $user = Auth::user();

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
        $user = Auth::user();

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

        $user = Auth::user();

        LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $this->currentLesson->id,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );

        $this->completedLessonIds[] = $this->currentLesson->id;

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
        $foundCurrent = false;
        foreach ($this->topics as $topic) {
            foreach ($topic->lessons as $lesson) {
                if ($foundCurrent) {
                    $this->currentLesson = $lesson;
                    $this->lessonId = $lesson->id;
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
        $previousLesson = null;
        foreach ($this->topics as $topic) {
            foreach ($topic->lessons as $lesson) {
                if ($this->currentLesson && $lesson->id === $this->currentLesson->id) {
                    if ($previousLesson) {
                        $this->currentLesson = $previousLesson;
                        $this->lessonId = $previousLesson->id;
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
