<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Course;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $xml .= '<sitemap><loc>' . url('/sitemap-pages.xml') . '</loc><lastmod>' . now()->toW3cString() . '</lastmod></sitemap>';
        $xml .= '<sitemap><loc>' . url('/sitemap-courses.xml') . '</loc><lastmod>' . now()->toW3cString() . '</lastmod></sitemap>';
        $xml .= '<sitemap><loc>' . url('/sitemap-blog.xml') . '</loc><lastmod>' . now()->toW3cString() . '</lastmod></sitemap>';
        $xml .= '</sitemapindex>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function pages(): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Static pages
        $pages = [
            ['url' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => url('/courses'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['url' => url('/blog'), 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => url('/about'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => url('/contact'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['url' => url('/privacy-policy'), 'priority' => '0.5', 'changefreq' => 'yearly'],
            ['url' => url('/terms'), 'priority' => '0.5', 'changefreq' => 'yearly'],
        ];
        
        foreach ($pages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . $page['url'] . '</loc>';
            $xml .= '<lastmod>' . now()->toW3cString() . '</lastmod>';
            $xml .= '<changefreq>' . $page['changefreq'] . '</changefreq>';
            $xml .= '<priority>' . $page['priority'] . '</priority>';
            $xml .= '</url>';
        }
        
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function courses(): Response
    {
        $courses = Course::where('status', 'published')
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach ($courses as $course) {
            $xml .= '<url>';
            $xml .= '<loc>' . url('/courses/' . $course->slug) . '</loc>';
            $xml .= '<lastmod>' . $course->updated_at->toW3cString() . '</lastmod>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }
        
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function blog(): Response
    {
        $articles = Article::where('status', 'published')
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach ($articles as $article) {
            $xml .= '<url>';
            $xml .= '<loc>' . url('/blog/' . $article->slug) . '</loc>';
            $xml .= '<lastmod>' . $article->updated_at->toW3cString() . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }
        
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
