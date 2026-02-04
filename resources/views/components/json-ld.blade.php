@props([
    'type' => 'WebSite', // WebSite, Course, Article, Organization, BreadcrumbList, Product
    'data' => [],
])

@php
    $baseUrl = config('app.url');
    $siteName = 'DigitaLabs';
    
    $schemas = [];
    
    // Organization Schema (always include)
    $organization = [
        '@type' => 'Organization',
        '@id' => $baseUrl . '/#organization',
        'name' => $siteName,
        'url' => $baseUrl,
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/logo.png'),
            'width' => 200,
            'height' => 60,
        ],
        'sameAs' => [
            'https://instagram.com/digitalabs.id',
            'https://youtube.com/@digitalabs',
            'https://tiktok.com/@digitalabs.id',
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => '+62-812-3456-7890',
            'contactType' => 'customer service',
            'availableLanguage' => 'Indonesian',
        ],
    ];
    
    // WebSite Schema
    if ($type === 'WebSite') {
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => $baseUrl . '/#website',
            'url' => $baseUrl,
            'name' => $siteName,
            'description' => 'Platform belajar online terbaik untuk meningkatkan skill digital Anda.',
            'publisher' => ['@id' => $baseUrl . '/#organization'],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $baseUrl . '/courses?search={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
        $schemas[] = $organization;
    }
    
    // Course Schema
    if ($type === 'Course' && !empty($data)) {
        $course = $data;
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $course['title'] ?? '',
            'description' => $course['description'] ?? '',
            'provider' => [
                '@type' => 'Organization',
                'name' => $siteName,
                'sameAs' => $baseUrl,
            ],
            'image' => $course['image'] ?? '',
            'offers' => [
                '@type' => 'Offer',
                'price' => $course['price'] ?? 0,
                'priceCurrency' => 'IDR',
                'availability' => 'https://schema.org/InStock',
                'url' => $course['url'] ?? '',
            ],
            'hasCourseInstance' => [
                '@type' => 'CourseInstance',
                'courseMode' => 'online',
                'courseWorkload' => $course['duration'] ?? 'PT10H',
            ],
        ];
        if (isset($course['rating']) && $course['rating'] > 0) {
            $schemas[count($schemas) - 1]['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $course['rating'],
                'reviewCount' => $course['reviewCount'] ?? 1,
                'bestRating' => 5,
                'worstRating' => 1,
            ];
        }
    }
    
    // Article Schema
    if ($type === 'Article' && !empty($data)) {
        $article = $data;
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article['title'] ?? '',
            'description' => $article['description'] ?? '',
            'image' => $article['image'] ?? '',
            'datePublished' => $article['publishedAt'] ?? now()->toIso8601String(),
            'dateModified' => $article['modifiedAt'] ?? now()->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $article['author'] ?? 'DigitaLabs',
            ],
            'publisher' => $organization,
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $article['url'] ?? url()->current(),
            ],
        ];
    }
    
    // BreadcrumbList Schema
    if ($type === 'BreadcrumbList' && !empty($data)) {
        $items = [];
        foreach ($data as $index => $item) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
    
    // FAQ Schema
    if ($type === 'FAQPage' && !empty($data)) {
        $faqs = [];
        foreach ($data as $faq) {
            $faqs[] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ];
        }
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqs,
        ];
    }
@endphp

@foreach($schemas as $schema)
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endforeach
