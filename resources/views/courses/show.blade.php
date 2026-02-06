<x-layouts.public :title="$course->title . ' - DigitaLabs'">
    {{-- Breadcrumb --}}
    <section class="bg-gray-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ url('/') }}" class="text-gray-500 hover:text-orange-500">Home</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ url('/courses') }}" class="text-gray-500 hover:text-orange-500">Kelas</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-900 font-medium truncate max-w-xs">{{ $course->title }}</span>
            </nav>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:flex lg:gap-8">
                {{-- Left Content --}}
                <div class="lg:flex-1">
                    {{-- Course Header (Mobile) --}}
                    <div class="lg:hidden mb-6">
                        <span class="inline-block px-3 py-1 bg-orange-100 text-orange-600 text-sm font-medium rounded-full mb-3">
                            {{ $course->category->name ?? 'Course' }}
                        </span>
                        <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $course->title }}</h1>
                        
                        {{-- Rating --}}
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($reviewStats['average']) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600">
                                {{ number_format($reviewStats['average'], 1) }} ({{ $reviewStats['total'] }} ulasan)
                            </span>
                        </div>
                    </div>

                    {{-- Video/Image Preview --}}
                    <div class="relative rounded-2xl overflow-hidden mb-8">
                        @if($course->preview_video)
                            <div class="aspect-video bg-black">
                                <iframe 
                                    src="{{ $course->preview_video }}" 
                                    class="w-full h-full"
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @else
                            <img src="{{ $course->thumbnail_url ?? 'https://placehold.co/800x450/f97316/white?text=' . urlencode($course->title) }}" 
                                 alt="{{ $course->title }}"
                                 class="w-full aspect-video object-cover">
                        @endif
                    </div>

                    {{-- Course Header (Desktop) --}}
                    <div class="hidden lg:block mb-8">
                        <span class="inline-block px-3 py-1 bg-orange-100 text-orange-600 text-sm font-medium rounded-full mb-3">
                            {{ $course->category->name ?? 'Course' }}
                        </span>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $course->title }}</h1>
                        
                        {{-- Rating & Meta --}}
                        <div class="flex flex-wrap items-center gap-4 text-sm">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($reviewStats['average']) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="ml-2 text-gray-600">
                                    {{ number_format($reviewStats['average'], 1) }} ({{ $reviewStats['total'] }} ulasan)
                                </span>
                            </div>
                            <span class="text-gray-400">•</span>
                            <span class="text-gray-600">{{ $totalLessons }} video pembelajaran</span>
                            @if($totalDuration > 0)
                            <span class="text-gray-400">•</span>
                            <span class="text-gray-600">{{ floor($totalDuration / 3600) }} jam {{ floor(($totalDuration % 3600) / 60) }} menit</span>
                            @endif
                        </div>
                    </div>

                    {{-- Tabs --}}
                    <div x-data="{ activeTab: 'description' }" class="mb-8">
                        {{-- Tab Headers --}}
                        <div class="flex border-b border-gray-200 mb-6 overflow-x-auto">
                            <button @click="activeTab = 'description'" 
                                    :class="{ 'border-orange-500 text-orange-600': activeTab === 'description', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'description' }"
                                    class="px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">
                                Deskripsi
                            </button>
                            <button @click="activeTab = 'curriculum'" 
                                    :class="{ 'border-orange-500 text-orange-600': activeTab === 'curriculum', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'curriculum' }"
                                    class="px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">
                                Kurikulum ({{ $totalLessons }} video)
                            </button>
                            <button @click="activeTab = 'reviews'" 
                                    :class="{ 'border-orange-500 text-orange-600': activeTab === 'reviews', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'reviews' }"
                                    class="px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">
                                Ulasan ({{ $reviewStats['total'] }})
                            </button>
                            <button @click="activeTab = 'tutor'" 
                                    :class="{ 'border-orange-500 text-orange-600': activeTab === 'tutor', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'tutor' }"
                                    class="px-4 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap">
                                Tutor{{ $course->tutors->count() > 1 ? ' (' . $course->tutors->count() . ')' : '' }}
                            </button>
                        </div>

                        {{-- Tab Content: Description --}}
                        <div x-show="activeTab === 'description'" x-cloak>
                            <div class="prose prose-orange max-w-none">
                                {!! $course->description !!}
                            </div>

                            {{-- What you'll learn --}}
                            @if($course->what_you_learn)
                            <div class="mt-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Yang Akan Anda Pelajari</h3>
                                <div class="grid md:grid-cols-2 gap-3">
                                    @foreach(explode("\n", $course->what_you_learn) as $item)
                                        @if(trim($item))
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span class="text-gray-700">{{ trim($item) }}</span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Requirements --}}
                            @if($course->requirements)
                            <div class="mt-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Persyaratan</h3>
                                <ul class="space-y-2">
                                    @foreach(explode("\n", $course->requirements) as $item)
                                        @if(trim($item))
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-orange-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <span class="text-gray-700">{{ trim($item) }}</span>
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>

                        {{-- Tab Content: Curriculum --}}
                        <div x-show="activeTab === 'curriculum'" x-cloak>
                            <div class="space-y-4" x-data="{ openTopic: 1 }">
                                @foreach($course->topics as $index => $topic)
                                <div class="border border-gray-200 rounded-xl overflow-hidden">
                                    <button @click="openTopic = openTopic === {{ $index + 1 }} ? null : {{ $index + 1 }}" 
                                            class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition">
                                        <div class="flex items-center">
                                            <span class="w-8 h-8 bg-orange-500 text-white rounded-lg flex items-center justify-center text-sm font-semibold mr-3">
                                                {{ $index + 1 }}
                                            </span>
                                            <div class="text-left">
                                                <h4 class="font-semibold text-gray-900">{{ $topic->title }}</h4>
                                                <p class="text-sm text-gray-500">{{ $topic->lessons->count() }} video</p>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openTopic === {{ $index + 1 }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="openTopic === {{ $index + 1 }}" x-transition.opacity.duration.200ms>
                                        <ul class="divide-y divide-gray-100">
                                            @foreach($topic->lessons as $lessonIndex => $lesson)
                                            <li class="flex items-center justify-between p-4 hover:bg-gray-50 transition group">
                                                <div class="flex items-center flex-1">
                                                    @if($userOwns || $lesson->is_free)
                                                        <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                        </svg>
                                                    @endif
                                                    
                                                    <div class="flex items-center gap-2 flex-1">
                                                        @if($lesson->is_free)
                                                            <a href="{{ route('courses.watch', ['courseSlug' => $course->slug, 'lessonUuid' => $lesson->uuid]) }}" 
                                                               class="text-gray-700 hover:text-orange-600 transition {{ $lesson->is_title_hidden ? '' : 'font-medium' }}">
                                                                @if($lesson->is_title_hidden)
                                                                    {{ $topic->title }} - {{ str_pad($lessonIndex + 1, 2, '0', STR_PAD_LEFT) }}
                                                                @else
                                                                    {{ $lesson->title }}
                                                                @endif
                                                            </a>
                                                        @else
                                                            <span class="text-gray-700 {{ $lesson->is_title_hidden ? '' : 'font-medium' }}">
                                                                @if($lesson->is_title_hidden)
                                                                    {{ $topic->title }} - {{ str_pad($lessonIndex + 1, 2, '0', STR_PAD_LEFT) }}
                                                                @else
                                                                    {{ $lesson->title }}
                                                                @endif
                                                            </span>
                                                        @endif
                                                        
                                                        @if($lesson->is_free)
                                                            <span class="px-2 py-0.5 bg-green-100 text-green-600 text-xs rounded font-medium">Free Preview</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($lesson->duration && !$lesson->is_title_hidden)
                                                <span class="text-sm text-gray-500 ml-4">{{ floor($lesson->duration / 3600) }}:{{ str_pad(floor(($lesson->duration % 3600) / 60), 2, '0', STR_PAD_LEFT) }}:{{ str_pad($lesson->duration % 60, 2, '0', STR_PAD_LEFT) }}</span>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Tab Content: Reviews --}}
                        <div x-show="activeTab === 'reviews'" x-cloak>
                            <livewire:course-reviews :course="$course" :reviewStats="$reviewStats" />
                        </div>

                        {{-- Tab Content: Tutor --}}
                        <div x-show="activeTab === 'tutor'" x-cloak>
                            @if($course->tutors->count() > 0)
                                <div class="space-y-6">
                                    @foreach($course->tutors as $tutor)
                                    <div class="bg-gray-50 rounded-2xl p-6">
                                        <div class="flex flex-col sm:flex-row items-start gap-6">
                                            {{-- Avatar --}}
                                            <div class="flex-shrink-0">
                                                <img src="{{ $tutor->avatar_url }}" 
                                                     alt="{{ $tutor->name }}"
                                                     class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-white shadow-lg">
                                            </div>

                                            {{-- Info --}}
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h3 class="text-xl font-bold text-gray-900">{{ $tutor->name }}</h3>
                                                    @if($tutor->pivot->is_primary ?? false)
                                                        <span class="px-2 py-1 bg-orange-100 text-orange-600 text-xs font-semibold rounded-full">Primary</span>
                                                    @endif
                                                </div>
                                                
                                                @if($tutor->title)
                                                    <p class="text-orange-600 font-medium mb-2">{{ $tutor->title }}</p>
                                                @endif

                                                @if($tutor->experience_years > 0)
                                                    <p class="text-sm text-gray-500 mb-3">
                                                        <span class="inline-flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            {{ $tutor->experience_years }}+ tahun pengalaman
                                                        </span>
                                                    </p>
                                                @endif

                                                @if($tutor->bio)
                                                    <p class="text-gray-600 leading-relaxed mb-4">{{ $tutor->bio }}</p>
                                                @endif

                                                {{-- Social Links --}}
                                                <div class="flex flex-wrap gap-2">
                                                    @if($tutor->website)
                                                        <a href="{{ $tutor->website }}" target="_blank" rel="noopener noreferrer" 
                                                           class="inline-flex items-center px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-100 hover:text-orange-600 transition">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                                            </svg>
                                                            Website
                                                        </a>
                                                    @endif
                                                    @if($tutor->linkedin)
                                                        <a href="{{ $tutor->linkedin }}" target="_blank" rel="noopener noreferrer" 
                                                           class="inline-flex items-center px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                            </svg>
                                                            LinkedIn
                                                        </a>
                                                    @endif
                                                    @if($tutor->youtube)
                                                        <a href="{{ $tutor->youtube }}" target="_blank" rel="noopener noreferrer" 
                                                           class="inline-flex items-center px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-red-50 hover:text-red-600 transition">
                                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                            </svg>
                                                            YouTube
                                                        </a>
                                                    @endif
                                                    @if($tutor->instagram)
                                                        <a href="{{ $tutor->instagram }}" target="_blank" rel="noopener noreferrer" 
                                                           class="inline-flex items-center px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-pink-50 hover:text-pink-600 transition">
                                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                            </svg>
                                                            Instagram
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <p class="text-gray-500">Informasi tutor belum tersedia.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Sidebar - Price Box --}}
                <div class="lg:w-96 flex-shrink-0">
                    <div class="sticky top-24">
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                            {{-- Mobile Price Header --}}
                            <div class="lg:hidden p-4 border-b border-gray-100">
                                <img src="{{ $course->thumbnail_url ?? 'https://placehold.co/400x225/f97316/white?text=' . urlencode($course->title) }}" 
                                     alt="{{ $course->title }}"
                                     class="w-full h-40 object-cover rounded-xl">
                            </div>

                            <div class="p-6">
                                {{-- Price --}}
                                <div class="mb-6">
                                    @if($course->sale_price && $course->sale_price < $course->price)
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-lg text-gray-400 line-through">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                            <span class="px-2 py-1 bg-red-100 text-red-600 text-xs font-semibold rounded">
                                                HEMAT {{ round((($course->price - $course->sale_price) / $course->price) * 100) }}%
                                            </span>
                                        </div>
                                        <p class="text-3xl font-bold text-orange-500">Rp {{ number_format($course->sale_price, 0, ',', '.') }}</p>
                                    @else
                                        <p class="text-3xl font-bold text-orange-500">Rp {{ number_format($course->price, 0, ',', '.') }}</p>
                                    @endif
                                </div>

                                {{-- CTA Button --}}
                                @if($userOwns)
                                    <a href="{{ url('/dashboard/learn/' . $course->slug) }}" 
                                       class="block w-full px-6 py-4 bg-green-500 text-white text-center font-semibold rounded-xl hover:bg-green-600 transition mb-4">
                                        Mulai Belajar
                                    </a>
                                @else
                                    <a href="{{ url('/checkout/' . $course->uuid) }}" 
                                       class="block w-full px-6 py-4 bg-orange-500 text-white text-center font-semibold rounded-xl hover:bg-orange-600 transition shadow-lg shadow-orange-500/30 mb-4">
                                        Beli Sekarang
                                    </a>
                                @endif

                                {{-- Course Includes --}}
                                <div class="border-t border-gray-100 pt-6">
                                    <h4 class="font-semibold text-gray-900 mb-4">Kelas ini termasuk:</h4>
                                    <ul class="space-y-3">
                                        <li class="flex items-center text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $totalLessons }} video pembelajaran Full HD
                                        </li>
                                        @if($totalDuration > 0)
                                        <li class="flex items-center text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ floor($totalDuration / 3600) }} jam {{ floor(($totalDuration % 3600) / 60) }} menit total durasi
                                        </li>
                                        @endif
                                        <li class="flex items-center text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Akses selamanya
                                        </li>
                                        <li class="flex items-center text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Update materi gratis
                                        </li>
                                        <li class="flex items-center text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                                            </svg>
                                            Akses grup Telegram private
                                        </li>
                                        <li class="flex items-center text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            File latihan & plugin
                                        </li>
                                        <li class="flex items-center text-sm text-gray-600">
                                            <svg class="w-5 h-5 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            Sertifikat kelulusan
                                        </li>
                                    </ul>
                                </div>

                                {{-- Share Buttons --}}
                                <div class="border-t border-gray-100 pt-6 mt-6">
                                    <p class="text-sm text-gray-500 mb-3">Bagikan kelas ini:</p>
                                    <div class="flex gap-2">
                                        <a href="https://wa.me/?text={{ urlencode($course->title . ' - ' . url('/courses/' . $course->slug)) }}" 
                                           target="_blank"
                                           class="w-10 h-10 flex items-center justify-center bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                            </svg>
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($course->title) }}&url={{ urlencode(url('/courses/' . $course->slug)) }}" 
                                           target="_blank"
                                           class="w-10 h-10 flex items-center justify-center bg-black text-white rounded-lg hover:bg-gray-800 transition">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                            </svg>
                                        </a>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/courses/' . $course->slug)) }}" 
                                           target="_blank"
                                           class="w-10 h-10 flex items-center justify-center bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                        </a>
                                        <button onclick="navigator.clipboard.writeText('{{ url('/courses/' . $course->slug) }}'); alert('Link berhasil disalin!')" 
                                                class="w-10 h-10 flex items-center justify-center bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Help Box --}}
                        <div class="mt-4 bg-orange-50 border border-orange-100 rounded-xl p-4">
                            <p class="text-sm text-gray-700 mb-2">Ada pertanyaan tentang kelas ini?</p>
                            <a href="https://wa.me/6289670883312?text={{ urlencode('Halo, saya ingin bertanya tentang kelas: ' . $course->title) }}" 
                               target="_blank"
                               class="inline-flex items-center text-sm text-orange-600 font-medium hover:text-orange-700">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Chat via WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Courses --}}
    @if($relatedCourses->count() > 0)
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Kelas Lainnya</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @foreach($relatedCourses as $related)
                <a href="{{ url('/courses/' . $related->slug) }}" 
                   class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition group">
                    <div class="relative">
                        <img src="{{ $related->thumbnail_url ?? 'https://placehold.co/600x400/f97316/white?text=' . urlencode($related->title) }}" 
                             alt="{{ $related->title }}"
                             class="w-full h-40 object-cover group-hover:scale-105 transition duration-300">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-orange-500 transition line-clamp-2">
                            {{ $related->title }}
                        </h3>
                        @if($related->sale_price && $related->sale_price < $related->price)
                            <span class="text-lg font-bold text-orange-500">Rp {{ number_format($related->sale_price, 0, ',', '.') }}</span>
                        @else
                            <span class="text-lg font-bold text-orange-500">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</x-layouts.public>
