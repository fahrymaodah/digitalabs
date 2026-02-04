<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display blog listing page with filters.
     */
    public function index(Request $request)
    {
        $query = Article::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Sorting
        $sortOptions = [
            'latest' => ['published_at', 'desc'],
            'oldest' => ['published_at', 'asc'],
        ];

        $sort = $request->get('sort', 'latest');
        if (isset($sortOptions[$sort])) {
            [$column, $direction] = $sortOptions[$sort];
            $query->orderBy($column, $direction);
        } else {
            $query->orderBy('published_at', 'desc');
        }

        $posts = $query->with(['category', 'author'])
            ->paginate(12)
            ->withQueryString();

        $categories = ArticleCategory::withCount(['articles' => function ($q) {
            $q->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        }])->having('articles_count', '>', 0)->get();

        $featuredPosts = Article::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        // Recent posts for sidebar
        $recentPosts = Article::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        return view('blog.index', compact('posts', 'categories', 'featuredPosts', 'recentPosts'));
    }

    /**
     * Display a single blog post.
     */
    public function show(string $slug)
    {
        $post = Article::where('slug', $slug)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with(['category', 'author'])
            ->firstOrFail();

        // Get related posts from same category
        $relatedPosts = Article::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // If not enough related posts from same category, fill with recent posts
        if ($relatedPosts->count() < 3) {
            $needed = 3 - $relatedPosts->count();
            $existingIds = $relatedPosts->pluck('id')->push($post->id)->toArray();
            
            $morePosts = Article::where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->whereNotIn('id', $existingIds)
                ->orderBy('published_at', 'desc')
                ->take($needed)
                ->get();

            $relatedPosts = $relatedPosts->merge($morePosts);
        }

        // Get categories for sidebar
        $categories = ArticleCategory::withCount(['articles' => function ($q) {
            $q->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now());
        }])->having('articles_count', '>', 0)->get();

        // Recent posts for sidebar
        $recentPosts = Article::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        // Previous and next posts for navigation
        $previousPost = Article::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('published_at', '<', $post->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        $nextPost = Article::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        return view('blog.show', compact('post', 'relatedPosts', 'categories', 'recentPosts', 'previousPost', 'nextPost'));
    }
}
