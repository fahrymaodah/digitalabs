<x-layouts.public>
    {{-- SEO Meta Tags --}}
    @push('meta')
    <meta name="description" content="{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}">
    <meta name="keywords" content="{{ is_array($post->tags) ? implode(', ', $post->tags) : '' }}">
    <meta name="author" content="{{ $post->author->name ?? 'DigitaLabs' }}">
    
    {{-- Open Graph --}}
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}">
    <meta property="og:url" content="{{ request()->url() }}">
    @if($post->featured_image)
        @php
            $ogImageUrl = str_starts_with($post->featured_image, 'http') 
                ? $post->featured_image 
                : Storage::url($post->featured_image);
        @endphp
        <meta property="og:image" content="{{ $ogImageUrl }}">
    @endif
    <meta property="article:published_time" content="{{ $post->published_at?->toIso8601String() }}">
    @if($post->category)
        <meta property="article:section" content="{{ $post->category->name }}">
    @endif
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->title }}">
    <meta name="twitter:description" content="{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 160) }}">
    @if($post->featured_image)
        @php
            $twitterImageUrl = str_starts_with($post->featured_image, 'http') 
                ? $post->featured_image 
                : Storage::url($post->featured_image);
        @endphp
        <meta name="twitter:image" content="{{ $twitterImageUrl }}">
    @endif
    @endpush

    @push('title')
    {{ $post->title }} - Blog DigitaLabs
    @endpush

    @push('styles')
    <style>
        /* Force overflow handling for blog content */
        .blog-content-wrapper {
            max-width: 100%;
            overflow-x: hidden;
        }
        .blog-content-wrapper * {
            max-width: 100%;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .blog-content-wrapper pre {
            overflow-x: auto;
            max-width: 100%;
            white-space: pre-wrap;
            word-break: break-all;
        }
        .blog-content-wrapper code {
            word-break: break-all;
        }
        .blog-content-wrapper table {
            display: block;
            overflow-x: auto;
            max-width: 100%;
        }
        .blog-content-wrapper img {
            max-width: 100%;
            height: auto;
        }
        .blog-content-wrapper iframe,
        .blog-content-wrapper embed,
        .blog-content-wrapper video {
            max-width: 100%;
        }
        .blog-content-wrapper a {
            word-break: break-all;
        }
        @media (max-width: 640px) {
            .blog-content-wrapper {
                font-size: 0.95rem;
            }
            .blog-content-wrapper pre,
            .blog-content-wrapper code {
                font-size: 0.75rem;
            }
        }
    </style>
    @endpush

    {{-- Hero Section with Featured Image --}}
    <section class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 overflow-hidden">
        <!-- Background Image (if exists) -->
        @if($post->featured_image)
        @php
            $featuredImageUrl = str_starts_with($post->featured_image, 'http') 
                ? $post->featured_image 
                : Storage::url($post->featured_image);
        @endphp
        <div class="absolute inset-0">
            <img src="{{ $featuredImageUrl }}" 
                 alt="{{ $post->title }}"
                 class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/80 to-gray-900/60"></div>
        </div>
        @else
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>
        @endif
        
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            {{-- Breadcrumb --}}
            <nav class="flex flex-wrap items-center space-x-2 text-sm text-gray-400 mb-8">
                <a href="{{ url('/') }}" class="hover:text-white transition flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Home
                </a>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('blog.index') }}" class="hover:text-white transition">Blog</a>
                @if($post->category)
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="hover:text-white transition">
                    {{ $post->category->name }}
                </a>
                @endif
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-orange-500 truncate max-w-[200px]">{{ $post->title }}</span>
            </nav>
            
            {{-- Category Badge --}}
            @if($post->category)
            <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" 
               class="inline-flex items-center px-4 py-2 bg-orange-500/20 text-orange-400 text-sm font-medium rounded-full hover:bg-orange-500/30 transition mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                {{ $post->category->name }}
            </a>
            @endif
            
            {{-- Title --}}
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-6">
                {{ $post->title }}
            </h1>
            
            {{-- Excerpt --}}
            @if($post->excerpt)
            <div class="relative pl-4 border-l-4 border-orange-500 mb-8">
                <p class="text-xl text-gray-300 italic">
                    {{ $post->excerpt }}
                </p>
            </div>
            @endif
            
            {{-- Meta Info --}}
            <div class="flex flex-wrap items-center gap-6 text-sm text-gray-400">
                {{-- Author --}}
                <div class="flex items-center">
                    @if($post->author)
                        @if($post->author->avatar_url)
                            <img src="{{ $post->author->avatar_url }}" 
                                 alt="{{ $post->author->name }}" 
                                 class="w-10 h-10 rounded-full mr-3 object-cover ring-2 ring-orange-500/50">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center mr-3 ring-2 ring-orange-500/50">
                                <span class="text-white font-bold">{{ strtoupper(substr($post->author->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div>
                            <p class="text-white font-medium">{{ $post->author->name }}</p>
                            <p class="text-gray-500 text-xs">Penulis</p>
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center mr-3">
                            <span class="text-white font-bold">D</span>
                        </div>
                        <div>
                            <p class="text-white font-medium">DigitaLabs</p>
                            <p class="text-gray-500 text-xs">Admin</p>
                        </div>
                    @endif
                </div>
                
                <div class="hidden sm:block w-px h-8 bg-gray-700"></div>
                
                {{-- Date --}}
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ $post->published_at->format('d M Y') }}</span>
                </div>
                
                {{-- Reading Time --}}
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $post->reading_time ?? '5' }} min read</span>
                </div>
                
                {{-- Views --}}
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span>{{ number_format($post->views_count ?? 0) }} views</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 overflow-hidden">
            <div class="grid lg:grid-cols-4 gap-10">
                {{-- Article Content (3 columns) --}}
                <article class="lg:col-span-3 blog-content-wrapper">
                    {{-- Featured Image --}}
                    @if($post->featured_image)
                    @php
                        $displayImageUrl = str_starts_with($post->featured_image, 'http') 
                            ? $post->featured_image 
                            : Storage::url($post->featured_image);
                    @endphp
                    <figure class="mb-10 rounded-2xl overflow-hidden shadow-xl">
                        <img src="{{ $displayImageUrl }}" 
                             alt="{{ $post->title }}"
                             class="w-full h-auto object-cover">
                    </figure>
                    @endif

                    {{-- Article Body --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 lg:p-10 overflow-hidden">
                        <div class="prose prose-sm sm:prose-lg prose-orange max-w-none
                                    prose-headings:font-bold prose-headings:text-gray-900
                                    prose-h2:text-xl sm:prose-h2:text-2xl prose-h2:mt-8 sm:prose-h2:mt-10 prose-h2:mb-4 prose-h2:pb-2 prose-h2:border-b prose-h2:border-gray-200
                                    prose-h3:text-lg sm:prose-h3:text-xl prose-h3:mt-6 sm:prose-h3:mt-8 prose-h3:mb-3
                                    prose-p:text-gray-700 prose-p:leading-relaxed prose-p:mb-4
                                    prose-a:text-orange-500 prose-a:no-underline hover:prose-a:underline prose-a:break-words
                                    prose-img:rounded-xl prose-img:shadow-md prose-img:max-w-full prose-img:h-auto
                                    prose-pre:bg-gray-900 prose-pre:text-gray-100 prose-pre:rounded-xl prose-pre:text-xs sm:prose-pre:text-sm
                                    prose-code:text-orange-600 prose-code:bg-orange-50 prose-code:px-1 prose-code:py-0.5 prose-code:rounded prose-code:font-normal prose-code:text-sm prose-code:break-all
                                    prose-blockquote:border-l-orange-500 prose-blockquote:bg-orange-50/50 prose-blockquote:py-3 prose-blockquote:px-4 prose-blockquote:rounded-r-xl prose-blockquote:not-italic
                                    prose-ul:list-disc prose-ol:list-decimal
                                    prose-li:text-gray-700 prose-li:mb-2
                                    prose-table:text-sm prose-th:bg-gray-50 prose-th:p-2 prose-td:p-2 prose-td:border prose-td:border-gray-200
                                    [&_iframe]:max-w-full [&_iframe]:w-full [&_embed]:max-w-full [&_video]:max-w-full
                                    [&_pre]:overflow-x-auto [&_pre]:max-w-full [&_table]:block [&_table]:overflow-x-auto [&_table]:w-full">
                            {!! $post->content !!}
                        </div>
                        
                        {{-- Tags --}}
                        @if($post->tags && count($post->tags) > 0)
                        <div class="mt-10 pt-8 border-t border-gray-200">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="text-gray-600 font-medium flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                    Tags:
                                </span>
                                @foreach($post->tags as $tag)
                                <span class="px-4 py-1.5 bg-gray-100 text-gray-600 text-sm rounded-full hover:bg-orange-50 hover:text-orange-600 transition cursor-pointer">
                                    {{ $tag }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        {{-- Share Buttons --}}
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <span class="text-gray-600 font-medium flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                    </svg>
                                    Bagikan artikel ini:
                                </span>
                                <x-public.share-buttons :url="request()->url()" :title="$post->title" />
                            </div>
                        </div>
                    </div>
                    
                    {{-- Author Box --}}
                    @if($post->author)
                    <div class="mt-8 bg-gradient-to-br from-orange-50 to-orange-100/50 rounded-2xl p-6 lg:p-8 border border-orange-200/50">
                        <div class="flex flex-col sm:flex-row items-start gap-5">
                            @if($post->author->avatar_url)
                                <img src="{{ $post->author->avatar_url }}" 
                                     alt="{{ $post->author->name }}" 
                                     class="w-20 h-20 rounded-2xl object-cover shadow-lg">
                            @else
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                                    <span class="text-3xl text-white font-bold">{{ strtoupper(substr($post->author->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="text-sm text-orange-600 font-medium mb-1">Tentang Penulis</p>
                                <h4 class="font-bold text-gray-900 text-xl mb-3">{{ $post->author->name }}</h4>
                                <p class="text-gray-600 leading-relaxed">
                                    {{ $post->author->bio ?? 'Penulis dan content creator di DigitaLabs. Membagikan tips dan insight seputar desain grafis, animasi, dan industri kreatif.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Navigation --}}
                    <div class="mt-8 grid sm:grid-cols-2 gap-4">
                        @if(isset($previousPost))
                        <a href="{{ route('blog.show', $previousPost->slug) }}" 
                           class="group flex items-center p-5 bg-white rounded-xl border border-gray-200 hover:border-orange-300 hover:shadow-md transition">
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-orange-500 mr-4 flex-shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500 mb-1">Sebelumnya</p>
                                <p class="font-medium text-gray-900 group-hover:text-orange-500 transition truncate">{{ $previousPost->title }}</p>
                            </div>
                        </a>
                        @else
                        <div></div>
                        @endif
                        
                        @if(isset($nextPost))
                        <a href="{{ route('blog.show', $nextPost->slug) }}" 
                           class="group flex items-center justify-end text-right p-5 bg-white rounded-xl border border-gray-200 hover:border-orange-300 hover:shadow-md transition">
                            <div class="min-w-0">
                                <p class="text-xs text-gray-500 mb-1">Selanjutnya</p>
                                <p class="font-medium text-gray-900 group-hover:text-orange-500 transition truncate">{{ $nextPost->title }}</p>
                            </div>
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-orange-500 ml-4 flex-shrink-0 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        @endif
                    </div>
                </article>
                
                {{-- Sidebar (1 column) --}}
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-24 space-y-6">
                        {{-- Table of Contents --}}
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100" 
                             x-data="{ 
                                 headings: [],
                                 init() {
                                     const content = document.querySelector('.prose');
                                     if (content) {
                                         this.headings = Array.from(content.querySelectorAll('h2, h3')).map((h, i) => {
                                             const id = 'heading-' + i;
                                             h.id = id;
                                             return { id, text: h.innerText, level: h.tagName };
                                         });
                                     }
                                 }
                             }">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                                Daftar Isi
                            </h3>
                            <nav class="space-y-2">
                                <template x-for="heading in headings" :key="heading.id">
                                    <a :href="'#' + heading.id" 
                                       :class="heading.level === 'H3' ? 'pl-4' : ''"
                                       class="block text-sm text-gray-600 hover:text-orange-500 transition py-1"
                                       x-text="heading.text">
                                    </a>
                                </template>
                            </nav>
                        </div>
                        
                        {{-- Sidebar Content --}}
                        <x-public.blog-sidebar 
                            :categories="$categories ?? collect()" 
                            :recentArticles="$recentPosts ?? collect()"
                            :tags="$tags ?? collect()"
                        />
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- Related Posts --}}
    @if($relatedPosts->isNotEmpty())
    <section class="py-12 lg:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Artikel Terkait
                </h2>
                <a href="{{ route('blog.index') }}" class="text-orange-500 hover:text-orange-600 font-medium flex items-center group">
                    <span>Lihat Semua</span>
                    <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedPosts->take(3) as $related)
                    <x-public.article-card :article="$related" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Section --}}
    <section class="py-16 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid3" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid3)" />
            </svg>
        </div>
        
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Mau Belajar Lebih Lanjut?
            </h2>
            <p class="text-gray-400 text-lg mb-8">
                Dapatkan akses ke kelas online premium kami dan pelajari skill desain dari para praktisi profesional
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ url('/courses') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-orange-700 transition shadow-lg hover:shadow-orange-500/25">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Jelajahi Kelas
                </a>
                <a href="{{ url('/blog') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-white/10 border border-white/20 text-white font-semibold rounded-xl hover:bg-white/20 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    Baca Artikel Lain
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
