<x-layouts.public title="Blog - Tips & Tutorial Desain | DigitaLabs">
    {{-- Hero Section with Background Pattern --}}
    <section class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-16 lg:py-20 overflow-hidden">
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
        
        <!-- Floating Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-orange-500/20 rounded-full blur-2xl"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 bg-orange-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex items-center space-x-2 text-sm text-gray-400 mb-6">
                <a href="{{ url('/') }}" class="hover:text-white transition">Home</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-orange-500">Blog</span>
            </nav>
            
            <div class="text-center">
                <div class="inline-flex items-center px-4 py-2 bg-orange-500/20 rounded-full mb-6">
                    <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <span class="text-orange-500 font-medium">Blog & Tutorial</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">
                    Insight & <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Tutorial</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto">
                    Tips, tutorial, dan insight seputar desain grafis, animasi, dan industri kreatif dari para praktisi profesional
                </p>
            </div>
        </div>
    </section>

    {{-- Featured Article --}}
    @if($featuredPosts->isNotEmpty())
    <section class="py-12 bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php $mainFeatured = $featuredPosts->first(); @endphp
            <div class="grid lg:grid-cols-2 gap-8 items-center">
                <!-- Image -->
                <div class="relative group">
                    <a href="{{ route('blog.show', $mainFeatured->slug) }}" class="block overflow-hidden rounded-2xl">
                        @if($mainFeatured->featured_image_url)
                            <img src="{{ $mainFeatured->featured_image_url }}" 
                                 alt="{{ $mainFeatured->title }}"
                                 class="w-full aspect-[4/3] object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full aspect-[4/3] bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                                <svg class="w-20 h-20 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                        @endif
                    </a>
                    <div class="absolute top-4 left-4 flex items-center space-x-2">
                        <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full shadow-lg flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Featured
                        </span>
                        @if($mainFeatured->category)
                            <span class="px-3 py-1 bg-orange-500 text-white text-xs font-medium rounded-full shadow-lg">
                                {{ $mainFeatured->category->name }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Content -->
                <div class="lg:pl-4">
                    <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $mainFeatured->published_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $mainFeatured->reading_time ?? '5' }} min read</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('blog.show', $mainFeatured->slug) }}">
                        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 hover:text-orange-500 transition mb-4 line-clamp-3">
                            {{ $mainFeatured->title }}
                        </h2>
                    </a>
                    
                    <p class="text-gray-600 text-lg mb-6 line-clamp-3">
                        {{ $mainFeatured->excerpt }}
                    </p>
                    
                    <a href="{{ route('blog.show', $mainFeatured->slug) }}" 
                       class="inline-flex items-center px-6 py-3 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition group">
                        <span>Baca Selengkapnya</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            {{-- More Featured Articles --}}
            @if($featuredPosts->count() > 1)
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10 pt-10 border-t">
                @foreach($featuredPosts->skip(1)->take(3) as $featured)
                    <x-public.article-card :article="$featured" featured />
                @endforeach
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Main Content with Sidebar --}}
    <section class="py-12 lg:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Filter Bar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
                <div class="flex items-center space-x-2 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span class="font-medium">{{ $posts->total() }} Artikel</span>
                    @if(request('search') || request('category'))
                        <span class="text-gray-400">|</span>
                        @if(request('search'))
                            <span>Pencarian: "<span class="font-medium text-orange-500">{{ request('search') }}</span>"</span>
                        @endif
                        @if(request('category'))
                            <span>Kategori: <span class="font-medium text-orange-500">{{ $categories->firstWhere('slug', request('category'))?->name }}</span></span>
                        @endif
                        <a href="{{ route('blog.index') }}" class="text-red-500 hover:text-red-600 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
                
                {{-- Sort Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" 
                            class="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-200 rounded-lg hover:border-orange-500 transition">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                        </svg>
                        <span class="text-sm text-gray-700">
                            {{ request('sort') === 'oldest' ? 'Terlama' : (request('sort') === 'popular' ? 'Terpopuler' : 'Terbaru') }}
                        </span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         x-cloak
                         @click.away="open = false"
                         x-transition
                         class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden z-10">
                        @foreach([
                            'latest' => 'Terbaru',
                            'oldest' => 'Terlama',
                            'popular' => 'Terpopuler'
                        ] as $key => $label)
                        <a href="{{ route('blog.index', array_merge(request()->all(), ['sort' => $key])) }}" 
                           class="block px-4 py-2 text-sm transition {{ request('sort', 'latest') === $key ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="grid lg:grid-cols-4 gap-8">
                {{-- Posts Grid (3 columns) --}}
                <div class="lg:col-span-3">
                    @if($posts->isEmpty())
                    <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Artikel tidak ditemukan</h3>
                        <p class="text-gray-600 mb-6">Coba ubah kata kunci pencarian atau filter kategori</p>
                        <a href="{{ route('blog.index') }}" class="inline-flex items-center px-6 py-3 bg-orange-500 text-white font-medium rounded-xl hover:bg-orange-600 transition">
                            Lihat Semua Artikel
                        </a>
                    </div>
                    @else
                    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($posts as $post)
                            <x-public.article-card :article="$post" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($posts->hasPages())
                    <div class="mt-10">
                        {{ $posts->links() }}
                    </div>
                    @endif
                    @endif
                </div>
                
                {{-- Sidebar (1 column) --}}
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-24">
                        <x-public.blog-sidebar 
                            :categories="$categories" 
                            :recentArticles="$recentPosts ?? collect()"
                            :tags="$tags ?? collect()"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Newsletter CTA --}}
    <section class="py-16 lg:py-20 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid2" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid2)" />
            </svg>
        </div>
        
        <div class="absolute top-10 left-10 w-32 h-32 bg-orange-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 bg-orange-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-orange-500/20 rounded-full mb-6">
                <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="text-orange-500 font-medium">Newsletter</span>
            </div>
            
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Dapatkan Update <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Artikel Terbaru</span>
            </h2>
            <p class="text-gray-400 mb-8 text-lg">
                Subscribe newsletter kami untuk mendapatkan tips dan tutorial desain langsung di inbox Anda. Gratis!
            </p>
            
            <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                <input type="email" 
                       placeholder="Masukkan email Anda" 
                       class="flex-1 px-5 py-4 bg-white/10 border border-white/20 text-white placeholder-gray-400 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                <button type="submit" 
                        class="px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-orange-700 transition shadow-lg hover:shadow-orange-500/25">
                    Subscribe
                </button>
            </form>
            
            <p class="text-gray-500 text-sm mt-4">
                Kami tidak akan spam. Unsubscribe kapan saja.
            </p>
        </div>
    </section>
</x-layouts.public>
