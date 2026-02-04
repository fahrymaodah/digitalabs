@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'image' => null,
    'type' => 'website',
    'author' => null,
    'publishedTime' => null,
    'modifiedTime' => null,
    'section' => null,
    'canonical' => null,
])

@php
    $siteName = 'DigitaLabs';
    $defaultDescription = 'Platform belajar online terbaik untuk meningkatkan skill digital Anda. Video berkualitas HD, akses selamanya, dan materi selalu update.';
    $defaultImage = asset('images/og-default.jpg');
    
    $metaTitle = $title ? "{$title} - {$siteName}" : "{$siteName} - Platform Belajar Online";
    $metaDescription = $description ?? $defaultDescription;
    $metaImage = $image ?? $defaultImage;
    $metaUrl = $canonical ?? url()->current();
@endphp

{{-- Primary Meta Tags --}}
<title>{{ $metaTitle }}</title>
<meta name="title" content="{{ $metaTitle }}">
<meta name="description" content="{{ $metaDescription }}">
@if($keywords)
<meta name="keywords" content="{{ $keywords }}">
@endif
@if($author)
<meta name="author" content="{{ $author }}">
@endif
<meta name="robots" content="index, follow">
<meta name="language" content="Indonesian">
<meta name="revisit-after" content="7 days">
<link rel="canonical" href="{{ $metaUrl }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $metaUrl }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ $metaImage }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="id_ID">
@if($type === 'article')
    @if($publishedTime)
    <meta property="article:published_time" content="{{ $publishedTime }}">
    @endif
    @if($modifiedTime)
    <meta property="article:modified_time" content="{{ $modifiedTime }}">
    @endif
    @if($section)
    <meta property="article:section" content="{{ $section }}">
    @endif
    @if($author)
    <meta property="article:author" content="{{ $author }}">
    @endif
@endif

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $metaUrl }}">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $metaImage }}">

{{-- Additional slots for page-specific meta --}}
{{ $slot ?? '' }}
