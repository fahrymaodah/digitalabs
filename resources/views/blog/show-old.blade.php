<x-layouts.public title="{{ $post->title }} - Blog DigitaLabs">
    {{-- Breadcrumb --}}
    <section class="bg-gray-50 py-4 border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-orange-500 transition">Home</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('blog.index') }}" class="hover:text-orange-500 transition">Blog</a>
                @if($post->category)
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="hover:text-orange-500 transition">
                    {{ $post->category->name }}
                </a>
                @endif
            </nav>
        </div>
    </section>

    {{-- Article Header --}}
    <article class="py-8 lg:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Meta --}}
            <header class="mb-8">
                @if($post->category)
                <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" 
                   class="inline-block px-4 py-1.5 bg-orange-100 text-orange-600 text-sm font-medium rounded-full hover:bg-orange-200 transition mb-4">
                    {{ $post->category->name }}
                </a>
                @endif

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight mb-6">
                    {{ $post->title }}
                </h1>

                @if($post->excerpt)
                <p class="text-xl text-gray-600 mb-6">
                    {{ $post->excerpt }}
                </p>
                @endif

                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                    @if($post->author)
                    <div class="flex items-center">
                        @if($post->author->avatar_url)
                            <img src="{{ $post->author->avatar_url }}" 
                                 alt="{{ $post->author->name }}" 
                                 class="w-10 h-10 rounded-full mr-3 object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center mr-3">
                                <span class="text-orange-600 font-semibold">{{ substr($post->author->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900">{{ $post->author->name }}</p>
                            <p class="text-gray-500">Penulis</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center gap-4 ml-auto">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $post->published_at->format('d M Y') }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $post->reading_time ?? '5' }} min read
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ number_format($post->views_count ?? 0) }} views
                        </span>
                    </div>
                </div>
            </header>

            {{-- Featured Image --}}
            @if($post->featured_image)
            <figure class="mb-10 rounded-2xl overflow-hidden">
                <img src="{{ Storage::url($post->featured_image) }}" 
                     alt="{{ $post->title }}"
                     class="w-full h-auto object-cover">
            </figure>
            @endif

            {{-- Article Content --}}
            <div class="prose prose-lg prose-orange max-w-none 
                        prose-headings:font-bold prose-headings:text-gray-900
                        prose-p:text-gray-700 prose-p:leading-relaxed
                        prose-a:text-orange-500 prose-a:no-underline hover:prose-a:underline
                        prose-img:rounded-xl prose-img:shadow-md
                        prose-pre:bg-gray-900 prose-pre:text-gray-100
                        prose-code:text-orange-600 prose-code:bg-orange-50 prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded
                        prose-blockquote:border-l-orange-500 prose-blockquote:bg-orange-50 prose-blockquote:py-1 prose-blockquote:px-4
                        prose-ul:list-disc prose-ol:list-decimal">
                {!! $post->content !!}
            </div>

            {{-- Tags (if any) --}}
            @if($post->tags && count($post->tags) > 0)
            <div class="mt-10 pt-8 border-t border-gray-200">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-gray-600 font-medium">Tags:</span>
                    @foreach($post->tags as $tag)
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">
                        {{ $tag }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Share Buttons --}}
            <div class="mt-10 pt-8 border-t border-gray-200">
                <div class="flex flex-wrap items-center gap-4">
                    <span class="text-gray-600 font-medium">Share artikel:</span>
                    <div class="flex gap-3">
                        {{-- Twitter/X --}}
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" 
                           target="_blank"
                           class="w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-gray-900 text-gray-600 hover:text-white rounded-full transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>

                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                           target="_blank"
                           class="w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-blue-600 text-gray-600 hover:text-white rounded-full transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>

                        {{-- LinkedIn --}}
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                           target="_blank"
                           class="w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-blue-700 text-gray-600 hover:text-white rounded-full transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>

                        {{-- WhatsApp --}}
                        <a href="https://wa.me/?text={{ urlencode($post->title . ' - ' . request()->url()) }}" 
                           target="_blank"
                           class="w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-green-500 text-gray-600 hover:text-white rounded-full transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>

                        {{-- Copy Link --}}
                        <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link berhasil disalin!')"
                                class="w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-orange-500 text-gray-600 hover:text-white rounded-full transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Author Box --}}
            @if($post->author)
            <div class="mt-10 p-6 bg-gray-50 rounded-2xl">
                <div class="flex items-start gap-4">
                    @if($post->author->avatar_url)
                        <img src="{{ $post->author->avatar_url }}" 
                             alt="{{ $post->author->name }}" 
                             class="w-16 h-16 rounded-full object-cover">
                    @else
                        <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl text-orange-600 font-semibold">{{ substr($post->author->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Ditulis oleh</p>
                        <h4 class="font-bold text-gray-900 text-lg mb-2">{{ $post->author->name }}</h4>
                        <p class="text-gray-600 text-sm">
                            {{ $post->author->bio ?? 'Penulis dan content creator di DigitaLabs. Membagikan tips dan insight seputar desain grafis dan industri kreatif.' }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </article>

    {{-- Related Posts --}}
    @if($relatedPosts->isNotEmpty())
    <section class="py-12 lg:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">📚 Artikel Terkait</h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($relatedPosts as $related)
                <a href="{{ route('blog.show', $related->slug) }}" 
                   class="group bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-lg hover:border-orange-200 transition duration-300">
                    <div class="aspect-video overflow-hidden">
                        @if($related->featured_image)
                            <img src="{{ Storage::url($related->featured_image) }}" 
                                 alt="{{ $related->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        @if($related->category)
                            <span class="inline-block px-3 py-1 bg-orange-100 text-orange-600 text-xs font-medium rounded-full mb-3">
                                {{ $related->category->name }}
                            </span>
                        @endif
                        <h3 class="font-bold text-gray-900 group-hover:text-orange-500 transition line-clamp-2 mb-2">
                            {{ $related->title }}
                        </h3>
                        <div class="flex items-center text-sm text-gray-500">
                            <span>{{ $related->published_at->format('d M Y') }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $related->reading_time ?? '5' }} min read</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('blog.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-orange-500 text-white font-medium rounded-xl hover:bg-orange-600 transition">
                    Lihat Semua Artikel
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif
</x-layouts.public>
