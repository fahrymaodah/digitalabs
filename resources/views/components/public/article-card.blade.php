@props([
    'article',
    'featured' => false,
])

@php
    $image = $article->featured_image_url ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800';
@endphp

<article class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
    <!-- Image Container -->
    <a href="{{ url('/blog/' . $article->slug) }}" class="block relative overflow-hidden aspect-[16/10]">
        <img 
            src="{{ $image }}" 
            alt="{{ $article->title }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            loading="lazy"
        >
        
        <!-- Category Badge -->
        @if($article->category)
            <div class="absolute top-3 left-3">
                <span class="px-3 py-1 bg-orange-500 text-white text-xs font-medium rounded-full shadow-lg">
                    {{ $article->category->name }}
                </span>
            </div>
        @endif
        
        <!-- Featured Badge -->
        @if($featured || $article->is_featured)
            <div class="absolute top-3 right-3">
                <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-medium rounded-full shadow-lg flex items-center space-x-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span>Featured</span>
                </span>
            </div>
        @endif
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </a>
    
    <!-- Content -->
    <div class="p-5">
        <!-- Meta Info -->
        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
            <div class="flex items-center space-x-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $article->published_at?->format('d M Y') ?? $article->created_at->format('d M Y') }}</span>
            </div>
            @if($article->reading_time)
                <div class="flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $article->reading_time }} min</span>
                </div>
            @endif
        </div>
        
        <!-- Title -->
        <a href="{{ url('/blog/' . $article->slug) }}">
            <h3 class="font-bold text-gray-800 group-hover:text-orange-500 transition-colors line-clamp-2 mb-2 {{ $featured ? 'text-xl' : 'text-lg' }}">
                {{ $article->title }}
            </h3>
        </a>
        
        <!-- Excerpt -->
        <p class="text-gray-600 text-sm line-clamp-2 mb-4">
            {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}
        </p>
        
        <!-- Footer -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <!-- Author -->
            <div class="flex items-center space-x-2">
                @if($article->author)
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr($article->author->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="text-sm text-gray-600">{{ $article->author->name ?? 'Admin' }}</span>
                @else
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-xs font-bold">
                        D
                    </div>
                    <span class="text-sm text-gray-600">DigitaLabs</span>
                @endif
            </div>
            
            <!-- Read More -->
            <a href="{{ url('/blog/' . $article->slug) }}" 
               class="inline-flex items-center text-orange-500 text-sm font-medium hover:text-orange-600 transition group/link">
                <span>Baca</span>
                <svg class="w-4 h-4 ml-1 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</article>
