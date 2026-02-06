@props([
    'categories' => collect(),
    'recentArticles' => collect(),
    'tags' => collect(),
])

<aside {{ $attributes->merge(['class' => 'space-y-8']) }}>
    <!-- Search Form -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Cari Artikel
        </h3>
        <form action="{{ url('/blog') }}" method="GET" class="relative">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Ketik kata kunci..."
                class="w-full pl-4 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition"
            >
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-400 hover:text-orange-500 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
        </form>
    </div>
    
    <!-- Categories -->
    @if($categories->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Kategori
            </h3>
            <ul class="space-y-2">
                @foreach($categories as $category)
                    <li>
                        <a href="{{ url('/blog?category=' . $category->slug) }}" 
                           class="flex items-center justify-between p-3 rounded-lg hover:bg-orange-50 transition group {{ request('category') === $category->slug ? 'bg-orange-50 text-orange-600' : 'text-gray-600' }}">
                            <span class="group-hover:text-orange-600 transition">{{ $category->name }}</span>
                            @if(isset($category->articles_count) || isset($category->posts_count))
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ request('category') === $category->slug ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-orange-500 group-hover:text-white' }} transition">
                                    {{ $category->articles_count ?? $category->posts_count ?? 0 }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <!-- Recent Articles -->
    @if($recentArticles->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Artikel Terbaru
            </h3>
            <div class="space-y-4">
                @foreach($recentArticles as $article)
                    <a href="{{ url('/blog/' . $article->slug) }}" class="flex items-start space-x-3 group">
                        <div class="w-16 h-16 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                            <img 
                                src="{{ $article->featured_image_url ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=200' }}" 
                                alt="{{ $article->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                loading="lazy"
                            >
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-800 group-hover:text-orange-500 transition line-clamp-2">
                                {{ $article->title }}
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $article->published_at?->format('d M Y') ?? $article->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
    
    <!-- Tags -->
    @if($tags->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                </svg>
                Tag Populer
            </h3>
            <div class="flex flex-wrap gap-2">
                @foreach($tags as $tag)
                    <a href="{{ url('/blog?tag=' . $tag->slug) }}" 
                       class="px-3 py-1.5 text-sm rounded-full border transition {{ request('tag') === $tag->slug ? 'bg-orange-500 text-white border-orange-500' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200' }}">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif
    
    <!-- Newsletter -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center space-x-2 mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <h3 class="font-bold">Newsletter</h3>
        </div>
        <p class="text-orange-100 text-sm mb-4">
            Dapatkan update artikel dan tips terbaru langsung ke inbox Anda.
        </p>
        <form action="#" method="POST" class="space-y-3">
            @csrf
            <input 
                type="email" 
                name="email" 
                placeholder="Email Anda"
                class="w-full px-4 py-3 rounded-xl bg-white/20 border border-white/30 placeholder-white/70 text-white focus:bg-white/30 focus:outline-none transition"
            >
            <button type="submit" class="w-full px-4 py-3 bg-white text-orange-500 rounded-xl font-semibold hover:bg-orange-50 transition">
                Langganan Gratis
            </button>
        </form>
    </div>
    
    <!-- Additional slot content -->
    {{ $slot ?? '' }}
</aside>
