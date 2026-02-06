<x-layouts.public>
    @push('title')
    {{ $lesson->title }} - {{ $course->title }} | DigitaLabs
    @endpush

    @push('meta')
    <meta name="description" content="Tonton preview gratis: {{ $lesson->title }} dari course {{ $course->title }}">
    <meta name="robots" content="noindex, nofollow">
    @endpush

    @push('styles')
    
    <link rel="stylesheet" href="https://cdn.plyr.io/3.8.4/plyr.css" />
    <style>
        :root {
            --plyr-color-main: #f97316;
        }

        .tutor-video-player {
            position: relative;
            background: #000;
            aspect-ratio: 16/9;
            overflow: hidden;
        }

        .tutor-video-player .loading-spinner {
            position: absolute;
            inset: 0;
            background: #000;
            z-index: 10;
        }

        .tutor-video-player .loading-spinner::before {
            content: "";
            box-sizing: border-box;
            position: absolute;
            top: calc(50% - 25px);
            left: calc(50% - 25px);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
            border-top-color: var(--plyr-color-main);
            animation: spinner 0.5s linear infinite;
        }

        @keyframes spinner {
            to { transform: rotate(360deg); }
        }

        .tutor-video-player .loading-spinner.hide {
            display: none !important;
        }

        .tutor-video-player .plyr {
            height: 100%;
            width: 100%;
        }

        .tutor-video-player .plyr--youtube iframe {
            top: -50%;
            height: 200% !important;
        }

        .tutor-video-player .plyr--youtube.plyr--paused.plyr--loading.plyr__poster-enabled .plyr__poster {
            opacity: 1 !important;
        }

        .tutor-video-player .plyr--youtube.plyr--paused.plyr__poster-enabled:not(.plyr--stopped) .plyr__poster {
            opacity: 0;
        }

        .plyr__controls {
            background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75));
        }
    </style>
    @endpush

    {{-- Video Player Section --}}
    <section class="bg-gray-900">
        <div class="max-w-7xl mx-auto">
            {{-- Video Container with Aspect Ratio --}}
            @if($videoId)
                <div class="tutor-video-player">
                    <div class="loading-spinner" aria-hidden="true"></div>
                    <div class="plyr__video-embed tutorPlayer">
                        <iframe
                            src="https://www.youtube.com/embed/{{ $videoId }}?&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1"
                            allowfullscreen
                            allowtransparency
                            allow="autoplay"
                        ></iframe>
                    </div>
                </div>
            @else
                <div class="relative w-full aspect-video flex items-center justify-center bg-gray-800 text-white">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-lg">Video tidak tersedia</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Content Section --}}
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                
                {{-- Main Content - Left Side --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Free Preview Badge & Navigation --}}
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Free Preview
                            </span>
                            <span class="text-sm text-gray-500">
                                {{ $freeLessonsCount }} dari {{ $totalLessons }} video tersedia gratis
                            </span>
                        </div>
                        
                        {{-- Navigation Buttons --}}
                        <div class="flex items-center gap-2">
                            @if($prevLesson)
                                <a href="{{ route('courses.watch', ['courseSlug' => $course->slug, 'lessonUuid' => $prevLesson->uuid]) }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Sebelumnya
                                </a>
                            @endif
                            @if($nextLesson)
                                <a href="{{ route('courses.watch', ['courseSlug' => $course->slug, 'lessonUuid' => $nextLesson->uuid]) }}" 
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                    Selanjutnya
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Lesson Title --}}
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                            {{ $lesson->title }}
                        </h1>
                        <p class="text-gray-600">
                            <a href="{{ route('courses.show', $course->slug) }}" class="text-orange-500 hover:text-orange-600 font-medium">
                                {{ $course->title }}
                            </a>
                            @if($lesson->topic)
                                <span class="mx-2">•</span>
                                <span>{{ $lesson->topic->title }}</span>
                            @endif
                        </p>
                    </div>

                    {{-- Lesson Description --}}
                    @if($lesson->description)
                        <div class="bg-gray-50 rounded-xl p-6">
                            <h3 class="font-semibold text-gray-900 mb-3">Tentang Video Ini</h3>
                            <div class="prose prose-gray max-w-none text-gray-700">
                                {!! nl2br(e($lesson->description)) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Free Lessons List --}}
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                Kurikulum Course
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $totalLessons }} video • {{ $freeLessonsCount }} video gratis</p>
                        </div>
                        <div class="max-h-[500px] overflow-y-auto" x-data="{ openTopic: 1 }">
                            @foreach($course->topics as $index => $topic)
                                <div class="border-b border-gray-100 last:border-0">
                                    <button @click="openTopic = openTopic === {{ $index + 1 }} ? 0 : {{ $index + 1 }}" 
                                            class="w-full px-6 py-4 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition">
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-medium text-gray-900">{{ $topic->title }}</span>
                                            <span class="text-xs text-gray-500">({{ $topic->lessons->count() }} video)</span>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-500 transition" :class="{ 'rotate-180': openTopic === {{ $index + 1 }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="openTopic === {{ $index + 1 }}" x-transition.opacity.duration.200ms class="divide-y divide-gray-50">
                                        @foreach($topic->lessons as $lessonIndex => $topicLesson)
                                            @if($topicLesson->is_free)
                                                <a href="{{ route('courses.watch', ['courseSlug' => $course->slug, 'lessonUuid' => $topicLesson->uuid]) }}"
                                                   class="flex items-center gap-4 p-4 hover:bg-orange-50 transition {{ $topicLesson->id === $lesson->id ? 'bg-orange-50 border-l-4 border-orange-500' : '' }}">
                                                    <div class="flex-shrink-0">
                                                        @if($topicLesson->id === $lesson->id)
                                                            <span class="w-8 h-8 flex items-center justify-center bg-orange-500 text-white rounded-full text-xs">
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </span>
                                                        @else
                                                            <span class="w-8 h-8 flex items-center justify-center bg-green-100 text-green-600 rounded-full">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 {{ $topicLesson->id === $lesson->id ? 'text-orange-600' : '' }} {{ $topicLesson->is_title_hidden ? '' : 'truncate' }}">
                                                            @if($topicLesson->is_title_hidden)
                                                                {{ $topic->title }} - {{ str_pad($lessonIndex + 1, 2, '0', STR_PAD_LEFT) }}
                                                            @else
                                                                {{ $topicLesson->title }}
                                                            @endif
                                                        </p>
                                                        <span class="inline-flex items-center text-xs text-green-600 mt-0.5">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Gratis
                                                        </span>
                                                    </div>
                                                    @if($topicLesson->duration && !$topicLesson->is_title_hidden)
                                                        <span class="text-xs text-gray-500 flex-shrink-0">
                                                            @php
                                                                $hours = floor($topicLesson->duration / 3600);
                                                                $minutes = floor(($topicLesson->duration % 3600) / 60);
                                                                $seconds = $topicLesson->duration % 60;
                                                            @endphp
                                                            @if($hours > 0)
                                                                {{ $hours }}:{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }}
                                                            @else
                                                                {{ $minutes }}:{{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }}
                                                            @endif
                                                        </span>
                                                    @endif
                                                </a>
                                            @else
                                                <div class="flex items-center gap-4 p-4 bg-gray-50">
                                                    <div class="flex-shrink-0">
                                                        <span class="w-8 h-8 flex items-center justify-center bg-gray-200 text-gray-400 rounded-full">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm text-gray-500 {{ $topicLesson->is_title_hidden ? '' : 'truncate' }}">
                                                            @if($topicLesson->is_title_hidden)
                                                                {{ $topic->title }} - {{ str_pad($lessonIndex + 1, 2, '0', STR_PAD_LEFT) }}
                                                            @else
                                                                {{ $topicLesson->title }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                    @if($topicLesson->duration && !$topicLesson->is_title_hidden)
                                                        <span class="text-xs text-gray-400 flex-shrink-0">
                                                            @php
                                                                $hours = floor($topicLesson->duration / 3600);
                                                                $minutes = floor(($topicLesson->duration % 3600) / 60);
                                                                $seconds = $topicLesson->duration % 60;
                                                            @endphp
                                                            @if($hours > 0)
                                                                {{ $hours }}:{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }}
                                                            @else
                                                                {{ $minutes }}:{{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }}
                                                            @endif
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Sidebar - Right Side --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-4 space-y-6">
                        {{-- CTA Card --}}
                        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl">
                            <div class="mb-4">
                                <span class="text-orange-200 text-sm font-medium">AKSES FULL COURSE</span>
                                <h3 class="text-xl font-bold mt-1">{{ $course->title }}</h3>
                            </div>
                            
                            <div class="flex items-baseline gap-2 mb-4">
                                @if($course->sale_price && $course->sale_price < $course->price)
                                    <span class="text-3xl font-bold">Rp {{ number_format($course->sale_price, 0, ',', '.') }}</span>
                                    <span class="text-orange-200 line-through text-lg">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-3xl font-bold">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                @endif
                            </div>

                            <ul class="space-y-2 mb-6 text-sm">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Akses {{ $totalLessons }} video pembelajaran
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Akses selamanya
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Sertifikat kelulusan
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-200" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Track progress belajar
                                </li>
                            </ul>

                            @auth('user')
                                <a href="{{ route('checkout.show', $course->slug) }}" 
                                   class="block w-full py-3 px-4 bg-white text-orange-600 font-bold rounded-xl text-center hover:bg-orange-50 transition shadow-lg">
                                    Beli Course Sekarang
                                </a>
                            @else
                                <a href="{{ route('filament.user.auth.register') }}" 
                                   class="block w-full py-3 px-4 bg-white text-orange-600 font-bold rounded-xl text-center hover:bg-orange-50 transition shadow-lg mb-3">
                                    Daftar & Beli Course
                                </a>
                                <a href="{{ route('filament.user.auth.login') }}" 
                                   class="block w-full py-2.5 px-4 bg-transparent border-2 border-white/50 text-white font-medium rounded-xl text-center hover:bg-white/10 transition text-sm">
                                    Sudah Punya Akun? Login
                                </a>
                            @endauth
                        </div>

                        {{-- Course Info Card --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-5">
                            <div class="flex items-center gap-4 mb-4">
                                @if($course->thumbnail_url)
                                    <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-16 h-12 object-cover rounded-lg">
                                @endif
                                <div>
                                    <p class="text-sm text-gray-500">Course</p>
                                    <a href="{{ route('courses.show', $course->slug) }}" class="font-semibold text-gray-900 hover:text-orange-500 transition line-clamp-2">
                                        {{ $course->title }}
                                    </a>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between text-sm text-gray-600 pt-4 border-t">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $totalLessons }} video
                                </div>
                                @if($course->category)
                                    <span class="px-2 py-1 bg-gray-100 rounded text-xs">{{ $course->category->name }}</span>
                                @endif
                            </div>

                            <a href="{{ route('courses.show', $course->slug) }}" 
                               class="block w-full mt-4 py-2.5 text-center text-orange-500 font-medium hover:text-orange-600 transition text-sm">
                                Lihat Detail Course →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')

    <script src="https://cdn.plyr.io/3.8.4/plyr.polyfilled.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if($videoId)
            const player = new Plyr('.tutorPlayer', {
                controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'settings', 'pip', 'airplay', 'fullscreen'],
                hideControls: true,
                resetOnEnd: false,
                keyboard: { focused: true, global: false },
                clickToPlay: true,
                youtube: {
                    noCookie: false,
                    rel: 0,
                    showinfo: 0,
                    iv_load_policy: 3,
                    modestbranding: 1
                }
            });

            function readyState_complete(callback) {
                document.addEventListener("readystatechange", function(event) {
                    if (event.target.readyState === "complete") {
                        if (typeof callback === "function") {
                            setTimeout(callback);
                        }
                    }
                });
            }

            readyState_complete(function() {
                setTimeout(function() {
                    var spinner = document.querySelector(".tutor-video-player .loading-spinner");
                    if (spinner) {
                        spinner.remove();
                    }
                }, 500);
            });

            player.on('ready', event => {
                const container = event.detail.plyr.elements.container;
                if (container) {
                    container.addEventListener('contextmenu', e => e.preventDefault());
                }
            });

            // EXACT WordPress poster opacity control (tutor-front.js)
            player.on('play', event => {
                const poster = document.querySelector('.plyr--youtube.plyr__poster-enabled .plyr__poster');
                if (poster) {
                    poster.style.opacity = '0';
                }
            });

            player.on('pause', event => {
                const poster = document.querySelector('.plyr--youtube.plyr__poster-enabled .plyr__poster');
                if (poster) {
                    poster.style.opacity = '1';
                }
            });
            @endif
        });
    </script>
    @endpush
</x-layouts.public>
