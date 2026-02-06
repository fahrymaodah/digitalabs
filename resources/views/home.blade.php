<x-layouts.public
    title="Belajar Desain & Animasi Online"
    description="Platform kursus online untuk belajar desain grafis, animasi, dan cara menghasilkan uang dari microstock. Dibimbing tutor berpengalaman 9+ tahun."
    keywords="kursus online, belajar desain, animasi, microstock, adobe, after effects, illustrator"
>
    @push('jsonld')
    <x-json-ld type="WebSite" />
    @endpush

    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-orange-50 via-white to-orange-50">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-orange-200 rounded-full opacity-20 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-orange-300 rounded-full opacity-20 blur-3xl"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                {{-- Left Content --}}
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center px-4 py-2 bg-orange-100 text-orange-700 rounded-full text-sm font-medium mb-6">
                        🎉 Selamat Datang di DigitaLabs
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
                        Kelas Online <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-orange-600">Desain & Animasi</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 max-w-xl mx-auto lg:mx-0">
                        Kelas online yang berfokus pada bagaimana cara menghasilkan uang dari internet dengan mengandalkan karya desain dan animasi. Dibimbing oleh tutor profesional dengan pengalaman lebih dari 9 tahun.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ url('/courses') }}" class="w-full sm:w-auto px-8 py-4 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition shadow-lg shadow-orange-500/30 text-center">
                            Lihat Semua Course
                        </a>
                        <a href="#why-us" class="w-full sm:w-auto px-8 py-4 bg-white text-gray-700 font-semibold rounded-xl border-2 border-gray-200 hover:border-orange-500 hover:text-orange-500 transition text-center">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                    
                    {{-- Stats --}}
                    <div class="flex items-center justify-center lg:justify-start gap-8 mt-10 pt-10 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['students'] ?? 500) }}+</p>
                            <p class="text-sm text-gray-500">Students</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['courses'] ?? 10 }}+</p>
                            <p class="text-sm text-gray-500">Courses</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-orange-500">4.9</p>
                            <p class="text-sm text-gray-500">Rating</p>
                        </div>
                    </div>
                </div>
                
                {{-- Right Content - Hero Image --}}
                <div class="relative">
                    <div class="relative z-10">
                        <img src="{{ asset('images/svg/freelancer.svg') }}" alt="Learning Illustration" 
                             class="w-full max-w-lg mx-auto">
                    </div>
                    
                    {{-- Floating Testimonial Card --}}
                    @if(isset($featuredTestimonial))
                    <div class="absolute -bottom-4 -left-4 lg:left-0 bg-white rounded-2xl shadow-xl p-4 max-w-xs z-20">
                        <div class="flex items-start space-x-3">
                            <img src="{{ $featuredTestimonial->avatar_url }}" 
                                 alt="{{ $featuredTestimonial->name }}"
                                 class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <div class="flex items-center mb-1">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-2">"{{ Str::limit($featuredTestimonial->content, 80) }}"</p>
                                <p class="text-xs font-medium text-gray-900 mt-1">{{ $featuredTestimonial->name }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Why Choose Us Section --}}
    <section id="why-us" class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    🔑 Kenapa Belajar di <span class="text-orange-500">DigitaLabs</span>?
                </h2>
                <p class="text-gray-600">
                    Kami menyediakan pengalaman belajar terbaik dengan berbagai keunggulan yang membantu Anda mencapai goals.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Benefit 1 --}}
                <div class="bg-gradient-to-br from-orange-50 to-white p-6 rounded-2xl border border-orange-100 hover:shadow-lg hover:shadow-orange-500/10 transition group">
                    <div class="w-14 h-14 bg-orange-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Tutor Berpengalaman</h3>
                    <p class="text-gray-600">Dibimbing oleh tutor berpengalaman lebih dari 9 tahun di bidang asset digital, animasi dan desain.</p>
                </div>

                {{-- Benefit 2 --}}
                <div class="bg-gradient-to-br from-orange-50 to-white p-6 rounded-2xl border border-orange-100 hover:shadow-lg hover:shadow-orange-500/10 transition group">
                    <div class="w-14 h-14 bg-orange-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Private Grup Support</h3>
                    <p class="text-gray-600">Dukungan grup private Telegram aktif seumur hidup dan dibimbing langsung oleh tutor.</p>
                </div>

                {{-- Benefit 3 --}}
                <div class="bg-gradient-to-br from-orange-50 to-white p-6 rounded-2xl border border-orange-100 hover:shadow-lg hover:shadow-orange-500/10 transition group">
                    <div class="w-14 h-14 bg-orange-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Private Support Tutor</h3>
                    <p class="text-gray-600">Dapat akses premium langsung ke tutor jika ada kendala terkait belajar dan produksi asset digital.</p>
                </div>

                {{-- Benefit 4 --}}
                <div class="bg-gradient-to-br from-orange-50 to-white p-6 rounded-2xl border border-orange-100 hover:shadow-lg hover:shadow-orange-500/10 transition group">
                    <div class="w-14 h-14 bg-orange-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Materi Kelas Full HD</h3>
                    <p class="text-gray-600">Akses materi seumur hidup cukup sekali pembelian dengan kualitas video Full HD.</p>
                </div>

                {{-- Benefit 5 --}}
                <div class="bg-gradient-to-br from-orange-50 to-white p-6 rounded-2xl border border-orange-100 hover:shadow-lg hover:shadow-orange-500/10 transition group">
                    <div class="w-14 h-14 bg-orange-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Akses Selamanya</h3>
                    <p class="text-gray-600">Sekali beli, akses selamanya. Belajar kapan saja tanpa batasan waktu dan update materi gratis.</p>
                </div>

                {{-- Benefit 6 --}}
                <div class="bg-gradient-to-br from-orange-50 to-white p-6 rounded-2xl border border-orange-100 hover:shadow-lg hover:shadow-orange-500/10 transition group">
                    <div class="w-14 h-14 bg-orange-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">File Latihan & Plugin</h3>
                    <p class="text-gray-600">Disediakan file latihan dan plugin yang dibutuhkan untuk praktik langsung.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Courses Section --}}
    <section class="py-16 lg:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between mb-12">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        📚 Course Pilihan
                    </h2>
                    <p class="text-gray-600">Course terbaik yang akan membantu meningkatkan skill Anda</p>
                </div>
                <a href="{{ url('/courses') }}" class="mt-4 sm:mt-0 inline-flex items-center text-orange-500 hover:text-orange-600 font-medium transition">
                    Lihat Semua
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($courses as $course)
                <a href="{{ url('/courses/' . $course->slug) }}" class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group">
                    <div class="relative">
                        <img src="{{ $course->thumbnail_url ?? 'https://placehold.co/600x400/f97316/white?text=' . urlencode($course->title) }}" 
                             alt="{{ $course->title }}"
                             class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                        @if($course->sale_price && $course->sale_price < $course->price)
                        <div class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                            SALE
                        </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="px-3 py-1 bg-orange-100 text-orange-600 text-xs font-medium rounded-full">
                                {{ $course->category->name ?? 'Course' }}
                            </span>
                            <div class="flex items-center text-yellow-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm text-gray-600 ml-1">{{ number_format($course->reviews_avg_rating ?? 0, 1) }}</span>
                                <span class="text-sm text-gray-400 ml-1">({{ $course->reviews_count ?? 0 }})</span>
                            </div>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-orange-500 transition line-clamp-2">
                            {{ $course->title }}
                        </h3>
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ Str::limit($course->description, 100) }}</p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div>
                                @if($course->sale_price && $course->sale_price < $course->price)
                                    <span class="text-sm text-gray-400 line-through">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                    <span class="text-lg font-bold text-orange-500 ml-2">Rp {{ number_format($course->sale_price, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-lg font-bold text-orange-500">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <span class="text-sm text-gray-500">{{ $course->lessons_count ?? $course->lessons->count() }} video</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">Belum ada course tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Testimonials Section --}}
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    ⭐ Apa Kata Mereka?
                </h2>
                <p class="text-gray-600">
                    Ribuan student telah merasakan manfaat belajar di DigitaLabs
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($testimonials as $testimonial)
                <div class="bg-gradient-to-br from-orange-50 to-white p-6 rounded-2xl border border-orange-100">
                    <div class="flex items-center mb-4">
                        @for($i = 0; $i < ($testimonial->rating ?? 5); $i++)
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 mb-6 line-clamp-4">"{{ $testimonial->content }}"</p>
                    <div class="flex items-center">
                        <img src="{{ $testimonial->avatar_url }}" 
                             alt="{{ $testimonial->name }}"
                             class="w-12 h-12 rounded-full object-cover mr-4">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $testimonial->name }}</p>
                            <p class="text-sm text-gray-500">{{ $testimonial->job_title ?? 'Student' }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">Belum ada testimonial.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Affiliate Section --}}
    <section class="py-16 lg:py-24 bg-gradient-to-br from-orange-500 to-orange-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">
                        Dapatkan Penghasilan Tambahan
                    </h2>
                    <p class="text-orange-100 text-lg mb-8">
                        Bergabung dengan program affiliate DigitaLabs dan dapatkan komisi dari setiap penjualan yang Anda referensikan.
                    </p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span>Komisi hingga <strong>30%</strong> per penjualan</span>
                        </li>
                        <li class="flex items-center">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span>Cookie tracking <strong>30 hari</strong></span>
                        </li>
                        <li class="flex items-center">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span>Pembayaran tepat waktu setiap bulan</span>
                        </li>
                        <li class="flex items-center">
                            <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span>Dashboard lengkap untuk tracking</span>
                        </li>
                    </ul>
                    <a href="{{ url('/affiliate') }}" class="inline-flex items-center px-8 py-4 bg-white text-orange-600 font-semibold rounded-xl hover:bg-orange-50 transition shadow-lg">
                        Pelajari Program Affiliate
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
                <div class="hidden lg:block">
                    <img src="{{ asset('images/svg/remote-work.svg') }}" alt="Affiliate Program" class="w-full max-w-md mx-auto">
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ Section --}}
    <section class="py-16 lg:py-24 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    ❓ Yang Sering Ditanyakan (FAQ)
                </h2>
                <p class="text-gray-600">
                    Temukan jawaban untuk pertanyaan umum tentang DigitaLabs
                </p>
            </div>

            <div class="space-y-4" x-data="{ openFaq: null }">
                {{-- FAQ 1 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 1 ? null : 1" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Apa itu Digitalabs.id?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 1 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 1" x-collapse class="px-5 pb-5 text-gray-600">
                        Digitalabs.id adalah platform kelas online yang berfokus pada bagaimana cara menghasilkan uang dari internet dengan mengandalkan karya desain dan animasi, dengan dibimbing oleh tutor profesional yang berpengalaman lebih dari 9 tahun.
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 2 ? null : 2" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Apakah akses kursus berlaku seumur hidup?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 2 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 2" x-collapse class="px-5 pb-5 text-gray-600">
                        Ya! Sekali beli, akses selamanya. Anda bisa belajar kapan saja tanpa batasan waktu dan mendapatkan update materi secara gratis.
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 3 ? null : 3" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Apakah saya bisa belajar tanpa pengalaman sebelumnya?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 3 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 3" x-collapse class="px-5 pb-5 text-gray-600">
                        Tentu saja! Materi kami disusun dari dasar hingga mahir. Anda akan dibimbing step by step mulai dari yang paling sederhana. Ditambah support grup private untuk bertanya kapan saja.
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 4 ? null : 4" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Apakah kursus bisa diakses lewat HP?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 4 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 4" x-collapse class="px-5 pb-5 text-gray-600">
                        Ya, platform kami responsive dan bisa diakses dari HP, tablet, atau komputer. Untuk praktik, disarankan menggunakan komputer/laptop.
                    </div>
                </div>

                {{-- FAQ 5 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 5 ? null : 5" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Apakah disediakan file latihan dan plugin?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 5 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 5" x-collapse class="px-5 pb-5 text-gray-600">
                        Ya, semua file latihan dan plugin yang dibutuhkan akan disediakan dalam setiap course untuk Anda praktikkan langsung.
                    </div>
                </div>

                {{-- FAQ 6 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 6 ? null : 6" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Bagaimana jika saya mengalami kesulitan saat belajar?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 6 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 6" x-collapse class="px-5 pb-5 text-gray-600">
                        Anda bisa langsung bertanya di grup private Telegram atau menghubungi tutor langsung. Tim kami siap membantu menyelesaikan kendala Anda.
                    </div>
                </div>

                {{-- FAQ 7 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 7 ? null : 7" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Apakah pembayaran dilakukan sekali atau berlangganan?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 7 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 7" x-collapse class="px-5 pb-5 text-gray-600">
                        Pembayaran hanya sekali saja (one-time payment), bukan berlangganan. Setelah membeli, Anda mendapatkan akses selamanya tanpa biaya tambahan.
                    </div>
                </div>

                {{-- FAQ 8 --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = openFaq === 8 ? null : 8" 
                            class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-gray-50 transition">
                        <span class="font-semibold text-gray-900">Metode pembayaran apa saja yang tersedia?</span>
                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openFaq === 8 }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openFaq === 8" x-collapse class="px-5 pb-5 text-gray-600">
                        Kami menyediakan berbagai metode pembayaran: Virtual Account (BCA, Mandiri, BNI, BRI, dll), E-Wallet (OVO, DANA, ShopeePay, GoPay), QRIS, Kartu Kredit, dan Indomaret.
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Final CTA Section --}}
    <section class="py-16 lg:py-24 bg-gradient-to-br from-gray-900 to-gray-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Siap Meningkatkan Skill Anda?
            </h2>
            <p class="text-gray-400 text-lg mb-8 max-w-2xl mx-auto">
                Bergabung dengan ribuan student lainnya dan mulai perjalanan belajar Anda hari ini. Investasi terbaik adalah investasi untuk diri sendiri.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/courses') }}" class="w-full sm:w-auto px-8 py-4 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition shadow-lg shadow-orange-500/30">
                    Mulai Belajar Sekarang
                </a>
                <a href="{{ route('filament.user.auth.register') }}" class="w-full sm:w-auto px-8 py-4 bg-white/10 text-white font-semibold rounded-xl border border-white/20 hover:bg-white/20 transition">
                    Daftar Gratis
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>
