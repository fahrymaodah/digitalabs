<x-layouts.public title="Katalog Kelas - DigitaLabs">
    {{-- Hero Section with Animated Background --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-16 lg:py-24">
        {{-- Animated Background Elements --}}
        <div class="absolute inset-0">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-orange-500/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-orange-400/10 rounded-full blur-3xl animate-pulse delay-700"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-r from-orange-500/5 to-amber-500/5 rounded-full blur-3xl"></div>
        </div>
        
        {{-- Grid Pattern Overlay --}}
        <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml,%3Csvg width=\"40\" height=\"40\" viewBox=\"0 0 40 40\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"%23fff\" fill-opacity=\"1\" fill-rule=\"evenodd\"%3E%3Cpath d=\"M0 40L40 0H20L0 20M40 40V20L20 40\"/%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                {{-- Badge --}}
                <div class="inline-flex items-center px-4 py-2 bg-orange-500/20 backdrop-blur-sm border border-orange-500/30 rounded-full mb-6">
                    <svg class="w-5 h-5 text-orange-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="text-orange-300 font-medium text-sm">Belajar Skill Profesional</span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-6 leading-tight">
                    Temukan Kelas <br class="hidden md:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-400">Impianmu</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto">
                    Kuasai desain grafis, motion graphic, dan animasi dari mentor berpengalaman. 
                    Mulai dari nol hingga mahir.
                </p>
                
                {{-- Search Bar --}}
                <form action="{{ url('/courses') }}" method="GET" class="max-w-2xl mx-auto">
                    <div class="relative flex items-center bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-2">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari kelas... (contoh: After Effects, Photoshop)"
                               class="flex-1 px-4 py-3 bg-transparent text-white placeholder-gray-400 focus:outline-none">
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-amber-600 transition-all duration-300 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span class="hidden sm:inline">Cari</span>
                        </button>
                    </div>
                </form>

                {{-- Stats --}}
                <div class="flex flex-wrap justify-center gap-8 mt-10">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">{{ $courses->total() }}+</div>
                        <div class="text-gray-400 text-sm">Kelas Tersedia</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">5000+</div>
                        <div class="text-gray-400 text-sm">Siswa Aktif</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">4.9</div>
                        <div class="text-gray-400 text-sm">Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Category Pills --}}
    <section class="py-8 bg-gray-50 border-b border-gray-200 sticky top-16 lg:top-20 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-4 overflow-x-auto pb-2 scrollbar-hide">
                <a href="{{ url('/courses') }}" 
                   class="flex-shrink-0 px-5 py-2.5 rounded-xl font-medium transition-all duration-300 {{ !request('category') ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'bg-white text-gray-600 hover:bg-orange-50 hover:text-orange-600 border border-gray-200' }}">
                    Semua Kelas
                </a>
                @foreach($categories as $category)
                    <a href="{{ url('/courses?category=' . $category->id) }}" 
                       class="flex-shrink-0 px-5 py-2.5 rounded-xl font-medium transition-all duration-300 {{ request('category') == $category->id ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/30' : 'bg-white text-gray-600 hover:bg-orange-50 hover:text-orange-600 border border-gray-200' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Course Catalog Section --}}
    <section class="py-12 lg:py-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Filter & Sort Row --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div class="flex items-center space-x-4">
                    <p class="text-gray-600">
                        Menampilkan <span class="font-bold text-orange-500">{{ $courses->count() }}</span> dari 
                        <span class="font-bold">{{ $courses->total() }}</span> kelas
                    </p>
                </div>
                
                <form action="{{ url('/courses') }}" method="GET" class="flex items-center space-x-3">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <select name="sort" 
                            onchange="this.form.submit()"
                            class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition text-gray-600 font-medium">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </form>
            </div>

            {{-- Reset Filter --}}
            @if(request()->hasAny(['search', 'category', 'sort']))
            <div class="mb-6">
                <a href="{{ url('/courses') }}" class="inline-flex items-center text-sm text-orange-500 hover:text-orange-600 font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset Semua Filter
                </a>
            </div>
            @endif

            @if($courses->count() > 0)
            {{-- Course Grid with Modern Cards --}}
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($courses as $course)
                <a href="{{ url('/courses/' . $course->slug) }}" 
                   class="group relative bg-white rounded-3xl overflow-hidden shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-orange-500/20 transition-all duration-500 transform hover:-translate-y-2">
                    {{-- Gradient Border Effect --}}
                    <div class="absolute inset-0 bg-gradient-to-r from-orange-500 to-amber-500 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500" style="padding: 2px; margin: -2px;">
                        <div class="w-full h-full bg-white rounded-3xl"></div>
                    </div>
                    
                    <div class="relative">
                        {{-- Thumbnail with Overlay --}}
                        <div class="relative overflow-hidden">
                            <img src="{{ $course->thumbnail ?? 'https://placehold.co/600x400/f97316/white?text=' . urlencode($course->title) }}" 
                                 alt="{{ $course->title }}"
                                 class="w-full h-52 object-cover transform group-hover:scale-110 transition-transform duration-700">
                            
                            {{-- Gradient Overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            {{-- Badges --}}
                            <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                @if($course->sale_price && $course->sale_price < $course->price)
                                <div class="px-3 py-1.5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold rounded-full shadow-lg animate-pulse">
                                    🔥 DISKON {{ round((($course->price - $course->sale_price) / $course->price) * 100) }}%
                                </div>
                                @endif
                            </div>
                            
                            {{-- Play Button on Hover --}}
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                                <div class="w-16 h-16 bg-white/90 rounded-full flex items-center justify-center shadow-xl transform scale-75 group-hover:scale-100 transition-transform duration-300">
                                    <svg class="w-8 h-8 text-orange-500 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            {{-- Category Badge Bottom --}}
                            <div class="absolute bottom-4 left-4">
                                <span class="px-4 py-1.5 bg-white/95 backdrop-blur-sm text-orange-600 text-xs font-bold rounded-full shadow-lg">
                                    {{ $course->category->name ?? 'Course' }}
                                </span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6">
                            {{-- Title --}}
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-orange-500 transition-colors duration-300 line-clamp-2">
                                {{ $course->title }}
                            </h3>

                            {{-- Description --}}
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2 leading-relaxed">
                                {{ Str::limit(strip_tags($course->description), 100) }}
                            </p>

                            {{-- Meta Info --}}
                            <div class="flex flex-wrap items-center gap-4 mb-5 text-sm text-gray-500">
                                <span class="flex items-center bg-gray-100 px-3 py-1.5 rounded-lg">
                                    <svg class="w-4 h-4 mr-1.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    @php
                                        $lessonCount = $course->topics->sum(fn($t) => $t->lessons->count());
                                    @endphp
                                    {{ $lessonCount }} Video
                                </span>
                                <span class="flex items-center bg-gray-100 px-3 py-1.5 rounded-lg">
                                    <svg class="w-4 h-4 mr-1.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Lifetime
                                </span>
                                <span class="flex items-center text-yellow-500">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="font-semibold">{{ number_format($course->reviews_avg_rating ?? 5, 1) }}</span>
                                </span>
                            </div>

                            {{-- Price & CTA --}}
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div>
                                    @if($course->sale_price && $course->sale_price < $course->price)
                                        <span class="text-sm text-gray-400 line-through block">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                        <span class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-500">
                                            Rp {{ number_format($course->sale_price, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-amber-500">
                                            Rp {{ number_format($course->price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-amber-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-orange-500/30 transform group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($courses->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $courses->links() }}
            </div>
            @endif

            @else
            {{-- Empty State with Better Design --}}
            <div class="text-center py-20 bg-gradient-to-br from-gray-50 to-white rounded-3xl">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-orange-100 to-amber-100 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Kelas Tidak Ditemukan</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Coba ubah kata kunci atau filter pencarian Anda untuk menemukan kelas yang sesuai</p>
                <a href="{{ url('/courses') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-bold rounded-2xl hover:from-orange-600 hover:to-amber-600 transition-all duration-300 shadow-xl shadow-orange-500/30 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    Lihat Semua Kelas
                </a>
            </div>
            @endif
        </div>
    </section>

    {{-- Why Choose Us Section --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Kenapa Belajar di <span class="text-orange-500">DigitaLabs</span>?
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Kami menyediakan pengalaman belajar terbaik untuk Anda</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl border border-orange-100 text-center group hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-orange-500 to-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Akses Selamanya</h3>
                    <p class="text-gray-600 text-sm">Sekali bayar, akses kelas selamanya tanpa batas waktu</p>
                </div>
                
                <div class="p-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl border border-blue-100 text-center group hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Video HD Berkualitas</h3>
                    <p class="text-gray-600 text-sm">Materi video berkualitas tinggi dengan penjelasan detail</p>
                </div>
                
                <div class="p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-100 text-center group hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Support & Konsultasi</h3>
                    <p class="text-gray-600 text-sm">Tanya jawab langsung dengan mentor via grup eksklusif</p>
                </div>
                
                <div class="p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border border-purple-100 text-center group hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Sertifikat</h3>
                    <p class="text-gray-600 text-sm">Dapatkan sertifikat setelah menyelesaikan kelas</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section with Modern Design --}}
    <section class="py-16 relative overflow-hidden">
        {{-- Background --}}
        <div class="absolute inset-0 bg-gradient-to-r from-orange-500 via-orange-600 to-amber-500"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23fff\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full mb-6">
                <span class="text-white/90 font-medium">💬 Butuh Bantuan?</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">
                Punya Pertanyaan Seputar Kelas?
            </h2>
            <p class="text-orange-100 mb-8 text-lg max-w-2xl mx-auto">
                Tim kami siap membantu Anda memilih kelas yang tepat sesuai kebutuhan dan level skill Anda
            </p>
            <a href="https://wa.me/6289670883312" target="_blank" 
               class="inline-flex items-center px-8 py-4 bg-white text-orange-600 font-bold rounded-2xl hover:bg-orange-50 transition-all duration-300 shadow-2xl transform hover:scale-105">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Hubungi via WhatsApp
            </a>
        </div>
    </section>
</x-layouts.public>
