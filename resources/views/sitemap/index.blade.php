{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ url('/sitemap-pages.xml') }}</loc>
        <lastmod>{{ now()->toW3cString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap-courses.xml') }}</loc>
        <lastmod>{{ now()->toW3cString() }}</lastmod>
    </sitemap>
    <sitemap>
        <loc>{{ url('/sitemap-blog.xml') }}</loc>
        <lastmod>{{ now()->toW3cString() }}</lastmod>
    </sitemap>
</sitemapindex>
