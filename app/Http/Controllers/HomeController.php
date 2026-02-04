<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured courses (latest 6, published) with reviews for rating calculation
        $courses = Course::query()
            ->where('status', 'published')
            ->withCount('lessons')
            ->withCount('publishedReviews as reviews_count')
            ->withAvg('publishedReviews as reviews_avg_rating', 'rating')
            ->with('category')
            ->latest()
            ->take(6)
            ->get();

        // Get published testimonials
        $testimonials = Testimonial::query()
            ->where('is_published', true)
            ->orderBy('order')
            ->take(6)
            ->get();

        // Get featured testimonial for hero section
        $featuredTestimonial = $testimonials->first();

        // Stats
        $stats = [
            'students' => User::where('is_admin', false)->count(),
            'courses' => Course::where('status', 'published')->count(),
        ];

        return view('home', compact(
            'courses',
            'testimonials',
            'featuredTestimonial',
            'stats'
        ));
    }
}
