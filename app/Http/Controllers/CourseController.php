<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display course catalog page
     */
    public function index(Request $request)
    {
        $query = Course::with(['category', 'topics.lessons'])
            ->where('status', 'published')
            ->withCount(['topics', 'reviews'])
            ->withAvg('reviews', 'rating');

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'popular':
                $query->orderBy('reviews_count', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $courses = $query->paginate(9)->withQueryString();
        $categories = CourseCategory::orderBy('name')->get();

        return view('courses.index', compact('courses', 'categories'));
    }

    /**
     * Display course detail page
     */
    public function show(string $slug)
    {
        $course = Course::with([
            'category',
            'topics' => function ($query) {
                $query->orderBy('order');
            },
            'topics.lessons' => function ($query) {
                $query->orderBy('order');
            },
        ])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

        // Count total lessons
        $totalLessons = $course->topics->sum(function ($topic) {
            return $topic->lessons->count();
        });

        // Calculate total duration (assuming duration is stored in minutes)
        $totalDuration = $course->topics->sum(function ($topic) {
            return $topic->lessons->sum('duration');
        });

        // Get reviews statistics from ALL published reviews (not paginated)
        $allReviews = $course->publishedReviews()->get();
        $reviewStats = [
            'total' => $allReviews->count(),
            'average' => $allReviews->avg('rating') ?? 0,
            'distribution' => [
                5 => $allReviews->where('rating', 5)->count(),
                4 => $allReviews->where('rating', 4)->count(),
                3 => $allReviews->where('rating', 3)->count(),
                2 => $allReviews->where('rating', 2)->count(),
                1 => $allReviews->where('rating', 1)->count(),
            ]
        ];

        // Check if user already owns this course
        $userOwns = false;
        $userCourse = null;
        if (Auth::guard('user')->check()) {
            $userCourse = Auth::guard('user')->user()->courses()
                ->where('course_id', $course->id)
                ->first();
            $userOwns = $userCourse !== null;
        }

        // Get related courses (same category, exclude current)
        $relatedCourses = Course::where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->where('status', 'published')
            ->limit(3)
            ->get();

        return view('courses.show', compact(
            'course',
            'totalLessons',
            'totalDuration',
            'reviewStats',
            'userOwns',
            'userCourse',
            'relatedCourses'
        ));
    }

    /**
     * Watch free lesson (public access without login)
     */
    public function watch(string $courseSlug, string $lessonUuid)
    {
        // Get course with all topics and lessons
        $course = Course::with(['category', 'topics' => function ($query) {
                $query->orderBy('order');
            }, 'topics.lessons' => function ($query) {
                $query->orderBy('order');
            }])
            ->where('slug', $courseSlug)
            ->where('status', 'published')
            ->firstOrFail();

        // Get lesson by UUID and verify it's free
        $lesson = \App\Models\Lesson::where('uuid', $lessonUuid)
            ->whereHas('topic', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->firstOrFail();

        // Check if lesson is free
        if (!$lesson->is_free) {
            abort(403, 'Lesson ini tidak tersedia untuk preview gratis. Silakan beli course untuk mengakses semua materi.');
        }

        // Get all free lessons for prev/next navigation
        $freeLessons = \App\Models\Lesson::where('is_free', true)
            ->whereHas('topic', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->with('topic')
            ->orderBy('order')
            ->get();

        // Find current position in free lessons list
        $currentIndex = $freeLessons->search(fn($l) => $l->id === $lesson->id);
        $prevLesson = $currentIndex > 0 ? $freeLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $freeLessons->count() - 1 ? $freeLessons[$currentIndex + 1] : null;

        // Extract YouTube video ID
        $videoId = $this->extractYoutubeVideoId($lesson->youtube_url);

        // Course stats for display
        $totalLessons = $course->topics->sum(fn($topic) => $topic->lessons->count());
        $freeLessonsCount = $freeLessons->count();

        return view('courses.watch', compact(
            'course',
            'lesson',
            'freeLessons',
            'prevLesson',
            'nextLesson',
            'videoId',
            'totalLessons',
            'freeLessonsCount'
        ));
    }

    /**
     * Extract YouTube video ID from URL
     */
    private function extractYoutubeVideoId(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        
        return $matches[1] ?? null;
    }
}
