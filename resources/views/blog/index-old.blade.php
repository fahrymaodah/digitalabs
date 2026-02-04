<x-layouts.public title="Blog - Tips & Tutorial Desain | DigitaLabs">
    {{-- Hero Section --}}
    <section class="bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">
                Blog DigitaLabs
            </h1>
            <p class="text-lg text-white/90 max-w-2xl mx-auto">
                Tips, tutorial, dan insight seputar desain grafis, animasi, dan industri kreatif
            </p>
        </div>
    </section>

    {{-- Featured Posts --}}
    @if($featuredPosts->isNotEmpty())
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">📌 Artikel Pilihan</h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($featuredPosts as $featured)
                <a href="{{ route('blog.show', $featured->slug) }}" 
                   class="group bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-lg hover:border-orange-200 transition duration-300">
                    <div class="aspect-video overflow-hidden">
                        @if($featured->featured_image)
                            <img src="{{ Storage::url($featured->featured_image) }}" 
                                 alt="{{ $featured->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        @if($featured->category)
                            <span class="inline-block px-3 py-1 bg-orange-100 text-orange-600 text-xs font-medium rounded-full mb-3">
                                {{ $featured->category->name }}
                            </span>
                        @endif
                        <h3 class="font-bold text-gray-900 group-hover:text-orange-500 transition line-clamp-2 mb-2">
                            {{ $featured->title }}
                        </h3>
                        <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                            {{ $featured->excerpt }}
                        </p>
                        <div class="flex items-center text-sm text-gray-500">
                            <span>{{ $featured->published_at->format('d M Y') }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $featured->reading_time ?? '5' }} min read</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Main Content --}}
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:flex lg:gap-12">
                {{-- Sidebar --}}
                <aside class="lg:w-72 flex-shrink-0 mb-8 lg:mb-0">
                    <div class="lg:sticky lg:top-24 space-y-6">
                        {{-- Search --}}
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                            <h3 class="font-semibold text-gray-900 mb-4">🔍 Cari Artikel</h3>
                            <form action="{{ route('blog.index') }}" method="GET">
                                @if(request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                <div class="relative">
                                    <input type="text" 
                                           name="search" 
                                           value="{{ request('search') }}"
                                           placeholder="Ketik kata kunci..."
                                           class="w-full pl-4 pr-10 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-orange-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Categories --}}
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                            <h3 class="font-semibold text-gray-900 mb-4">📂 Kategori</h3>
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('blog.index', request()->except('category')) }}" 
                                       class="flex items-center justify-between py-2 px-3 rounded-lg transition {{ !request('category') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                        <span>Semua</span>
                                        <span class="text-sm bg-gray-100 px-2 py-0.5 rounded">{{ $posts->total() }}</span>
                                    </a>
                                </li>
                                @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('blog.index', array_merge(request()->except('category'), ['category' => $category->slug])) }}" 
                                       class="flex items-center justify-between py-2 px-3 rounded-lg transition {{ request('category') === $category->slug ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                        <span>{{ $category->name }}</span>
                                        <span class="text-sm bg-gray-100 px-2 py-0.5 rounded">{{ $category->posts_count }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Sort --}}
                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                            <h3 class="font-semibold text-gray-900 mb-4">📊 Urutkan</h3>
                            <div class="space-y-2">
                                @foreach([
                                    'latest' => 'Terbaru',
                                    'oldest' => 'Terlama',
                                    'popular' => 'Terpopuler'
                                ] as $key => $label)
                                <a href="{{ route('blog.index', array_merge(request()->all(), ['sort' => $key])) }}" 
                                   class="block py-2 px-3 rounded-lg transition {{ request('sort', 'latest') === $key ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                    {{ $label }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- Posts Grid --}}
                <div class="flex-1">
                    @if(request('search') || request('category'))
                    <div class="mb-6 flex items-center justify-between">
                        <p class="text-gray-600">
                            Menampilkan {{ $posts->total() }} artikel
                            @if(request('search'))
                                untuk "<span class="font-medium">{{ request('search') }}</span>"
                            @endif
                            @if(request('category'))
                                di kategori <span class="font-medium">{{ $categories->firstWhere('slug', request('category'))?->name }}</span>
                            @endif
                        </p>
                        <a href="{{ route('blog.index') }}" class="text-orange-500 hover:underline text-sm">
                            Reset filter
                        </a>
                    </div>
                    @endif

                    @if($posts->isEmpty())
                    <div class="text-center py-16">
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
                        <a href="{{ route('blog.show', $post->slug) }}" 
                           class="group bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-lg hover:border-orange-200 transition duration-300">
                            <div class="aspect-video overflow-hidden">
                                @if($post->featured_image)
                                    <img src="{{ Storage::url($post->featured_image) }}" 
                                         alt="{{ $post->title }}"
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
                                @if($post->category)
                                    <span class="inline-block px-3 py-1 bg-orange-100 text-orange-600 text-xs font-medium rounded-full mb-3">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                                <h3 class="font-bold text-gray-900 group-hover:text-orange-500 transition line-clamp-2 mb-2">
                                    {{ $post->title }}
                                </h3>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                    {{ $post->excerpt }}
                                </p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span>{{ $post->published_at->format('d M Y') }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $post->reading_time ?? '5' }} min read</span>
                                </div>
                            </div>
                        </a>
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
            </div>
        </div>
    </section>

    {{-- Newsletter CTA --}}
    <section class="py-12 lg:py-16 bg-orange-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                📬 Dapatkan Update Artikel Terbaru
            </h2>
            <p class="text-gray-600 mb-6">
                Subscribe newsletter kami untuk mendapatkan tips dan tutorial desain langsung di inbox Anda
            </p>
            <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                <input type="email" 
                       placeholder="Email Anda" 
                       class="flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <button type="submit" 
                        class="px-6 py-3 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition">
                    Subscribe
                </button>
            </form>
        </div>
    </section>
</x-layouts.public>
