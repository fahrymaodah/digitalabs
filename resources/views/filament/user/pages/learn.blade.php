<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdn.plyr.io/3.8.4/plyr.css" />
    {{-- YouTube Branding Hide Styles (same as watch.blade.php) --}}
    <style>
        :root {
            --plyr-color-main: #f97316;
        }

        /* Tutor LMS-like wrapper for YouTube iframe */
        .tutor-video-player {
            position: relative;
            background: #000;
            overflow: hidden;
        }

        /* Loading spinner overlay */
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
            border-top-color: #f97316;
            animation: learn-spinner 0.7s linear infinite;
        }

        @keyframes learn-spinner {
            to { transform: rotate(360deg); }
        }

        .tutor-video-player .loading-spinner.hide {
            display: none !important;
        }

        /* IMPORTANT: Let Plyr use its default YouTube embed crop */
        /* WordPress plyr.css: padding-bottom:240%; transform:translateY(-38.28125%) */
        /* DO NOT override this! Plyr handles YouTube branding hiding internally */

        /* Tutor Pro additional crop technique for YouTube - EXACT WordPress values */
        .tutor-video-player .plyr--youtube iframe {
            top: -50%;
            height: 200% !important;
        }
    </style>

    @if($this->course && $this->currentLesson)
        <div class="flex flex-col lg:flex-row gap-6">
            {{-- MOBILE: Progress Section (Order 1 - paling atas) --}}
            <div class="w-full lg:hidden order-1 bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $this->course->title }}</h3>
                
                @if($userOwnsCourse)
                    <div class="mt-3">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div id="mobile-course-progress-bar" 
                                 class="bg-amber-500 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $this->getCourseTotalDuration() > 0 ? min(100, round(($this->getCourseWatchedDuration() / $this->getCourseTotalDuration()) * 100, 1)) : 0 }}%"
                                 data-total-duration="{{ $this->getCourseTotalDuration() }}"></div>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span id="mobile-course-percent" 
                                  class="text-xs font-medium text-gray-900 dark:text-white"
                                  data-base-percent="{{ $this->getCourseTotalDuration() > 0 ? round((($this->getCourseWatchedDuration() - $this->getLessonWatchedSeconds($this->currentLesson)) / $this->getCourseTotalDuration()) * 100, 1) : 0 }}">
                                {{ $this->getCourseTotalDuration() > 0 ? round(($this->getCourseWatchedDuration() / $this->getCourseTotalDuration()) * 100, 1) : 0 }}% complete</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                <span id="mobile-course-progress" 
                                      data-base-duration="{{ $this->getCourseWatchedDuration() - $this->getLessonWatchedSeconds($this->currentLesson) }}"
                                      data-total-duration="{{ $this->getCourseTotalDuration() }}">{{ \App\Filament\User\Pages\Learn::formatDuration($this->getCourseWatchedDuration()) }}</span> / {{ \App\Filament\User\Pages\Learn::formatDuration($this->getCourseTotalDuration()) }} <span class="inline-block mx-2 text-lg text-amber-500">•</span> 
                                {{ count($this->completedLessonIds) }}/{{ $this->course->lessons()->count() }} lessons
                            </span>
                        </div>
                    </div>
                @else
                    <div class="mt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            <x-heroicon-o-eye class="w-4 h-4 mr-1" />
                            Free Preview
                        </span>
                    </div>
                @endif
            </div>

            {{-- DESKTOP: Sidebar dengan Progress + Curriculum --}}
            <div class="hidden lg:block w-full lg:w-80 flex-shrink-0">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow sticky top-4">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $this->course->title }}</h3>
                        
                        @if($userOwnsCourse)
                            {{-- Real-time Progress Bar --}}
                            <div class="mt-3">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div id="sidebar-course-progress-bar" 
                                         class="bg-amber-500 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $this->getCourseTotalDuration() > 0 ? min(100, round(($this->getCourseWatchedDuration() / $this->getCourseTotalDuration()) * 100, 1)) : 0 }}%"
                                         data-total-duration="{{ $this->getCourseTotalDuration() }}"></div>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span id="sidebar-course-percent" 
                                          class="text-xs font-medium text-gray-900 dark:text-white"
                                          data-base-percent="{{ $this->getCourseTotalDuration() > 0 ? round((($this->getCourseWatchedDuration() - $this->getLessonWatchedSeconds($this->currentLesson)) / $this->getCourseTotalDuration()) * 100, 1) : 0 }}">
                                        {{ $this->getCourseTotalDuration() > 0 ? round(($this->getCourseWatchedDuration() / $this->getCourseTotalDuration()) * 100, 1) : 0 }}% complete</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        <span id="sidebar-course-progress" 
                                              data-base-duration="{{ $this->getCourseWatchedDuration() - $this->getLessonWatchedSeconds($this->currentLesson) }}"
                                              data-total-duration="{{ $this->getCourseTotalDuration() }}">{{ \App\Filament\User\Pages\Learn::formatDuration($this->getCourseWatchedDuration()) }}</span> / {{ \App\Filament\User\Pages\Learn::formatDuration($this->getCourseTotalDuration()) }} <span class="inline-block mx-2 text-lg text-amber-500">•</span> 
                                        {{ count($this->completedLessonIds) }}/{{ $this->course->lessons()->count() }} lessons
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="mt-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    <x-heroicon-o-eye class="w-4 h-4 mr-1" />
                                    Free Preview
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="max-h-[60vh] overflow-y-auto">
                        @foreach($this->topics as $topic)
                            <div class="border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                <div class="p-4 bg-gray-50 dark:bg-gray-900">
                                    <h4 class="font-medium text-gray-900 dark:text-white text-sm">{{ $topic->title }}</h4>
                                    @if($userOwnsCourse)
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                            <span id="sidebar-topic-progress-{{ $topic->id }}" data-base-duration="{{ $this->getTopicWatchedDuration($topic) - $this->getLessonWatchedSeconds($this->currentLesson) }}">{{ \App\Filament\User\Pages\Learn::formatDuration($this->getTopicWatchedDuration($topic)) }}</span> / {{ \App\Filament\User\Pages\Learn::formatDuration($this->getTopicTotalDuration($topic)) }}
                                        </p>
                                    @endif
                                </div>
                                <ul>
                                    @foreach($topic->lessons as $lesson)
                                        <li>
                                            <button 
                                                wire:click="selectLesson({{ $lesson->id }})"
                                                class="w-full flex items-center gap-3 p-3 text-left hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors
                                                    {{ $this->currentLesson && $this->currentLesson->id === $lesson->id ? 'bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500' : '' }}">
                                                @if(in_array($lesson->id, $this->completedLessonIds))
                                                    <x-heroicon-s-check-circle class="w-5 h-5 text-green-500 flex-shrink-0" />
                                                @else
                                                    <x-heroicon-o-play-circle class="w-5 h-5 text-gray-400 flex-shrink-0" />
                                                @endif
                                                <div class="flex-1">
                                                    <span class="text-sm block {{ $this->currentLesson && $this->currentLesson->id === $lesson->id ? 'font-medium text-amber-600 dark:text-amber-400' : 'text-gray-700 dark:text-gray-300' }}">
                                                        {{ $lesson->title }}
                                                    </span>
                                                    @if($userOwnsCourse && $lesson->duration)
                                                        @if($this->currentLesson && $this->currentLesson->id === $lesson->id)
                                                            <span class="text-xs text-gray-400"><span id="sidebar-lesson-progress">{{ \App\Filament\User\Pages\Learn::formatDuration($this->getLessonWatchedSeconds($lesson)) }}</span> / {{ \App\Filament\User\Pages\Learn::formatDuration($lesson->duration) }}</span>
                                                        @else
                                                            <span class="text-xs text-gray-400">{{ \App\Filament\User\Pages\Learn::formatDuration($this->getLessonWatchedSeconds($lesson)) }} / {{ \App\Filament\User\Pages\Learn::formatDuration($lesson->duration) }}</span>
                                                        @endif
                                                    @elseif(!$userOwnsCourse && $lesson->duration)
                                                        <span class="text-xs text-gray-400">{{ \App\Filament\User\Pages\Learn::formatDuration($lesson->duration) }}</span>
                                                    @endif
                                                </div>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Video Player (Order 2 di mobile - tengah) --}}
            <div class="flex-1 order-2 lg:order-none">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                    {{-- Video - wire:ignore prevents Livewire from destroying the YouTube player --}}
                    <div class="aspect-video bg-black tutor-video-player" wire:ignore style="position: relative;">
                        {{-- Loading spinner (same as watch.blade.php) --}}
                        <div id="learn-loading-spinner" class="loading-spinner" aria-hidden="true"></div>
                        {{-- Plyr embed container (JavaScript controls content) --}}
                        <div id="youtube-player" class="plyr__video-embed tutorPlayer"
                            data-lesson-id="{{ $this->currentLesson->id }}" 
                            data-video-id="{{ $this->getYoutubeVideoId() }}" 
                            data-start-seconds="{{ $this->getCurrentLessonWatchedSeconds() }}"
                            data-is-completed="{{ in_array($this->currentLesson->id, $this->completedLessonIds) ? 'true' : 'false' }}"></div>
                        {{-- No-video message container - separate from youtube-player --}}
                        <div id="no-video-message" style="display: none; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: #000; align-items: center; justify-content: center;">
                            <div style="text-align: center;">
                                <svg style="width: 64px; height: 64px; margin: 0 auto 16px; display: block; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <p style="font-size: 18px; margin: 0; font-weight: 500; color: #9ca3af;">No video available</p>
                            </div>
                        </div>
                    </div>

                    {{-- Lesson Info --}}
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ $this->currentLesson->title }}
                                </h2>
                                @if($userOwnsCourse && $this->currentLesson->duration !== null)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        <x-heroicon-o-clock class="w-4 h-4 inline mr-1" />
                                        <span id="current-lesson-progress">{{ \App\Filament\User\Pages\Learn::formatDuration($this->getLessonWatchedSeconds($this->currentLesson)) }}</span> / {{ \App\Filament\User\Pages\Learn::formatDuration($this->currentLesson->duration) }}
                                    </p>
                                @elseif(!$userOwnsCourse && $this->currentLesson->duration !== null)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        <x-heroicon-o-clock class="w-4 h-4 inline mr-1" />
                                        {{ \App\Filament\User\Pages\Learn::formatDuration($this->currentLesson->duration) }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if($this->currentLesson->description)
                            <div class="text-gray-600 dark:text-gray-400 mb-4 prose prose-sm dark:prose-invert">
                                {!! $this->currentLesson->description !!}
                            </div>
                        @endif

                        {{-- Actions --}}
                        @if($userOwnsCourse)
                            <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button 
                                    wire:click="goToPreviousLesson"
                                    class="inline-flex items-center justify-center p-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 font-medium text-sm"
                                    title="Previous Lesson">
                                    <x-heroicon-o-chevron-left class="w-5 h-5" />
                                </button>

                                @if(!in_array($this->currentLesson->id, $this->completedLessonIds))
                                    <button 
                                        wire:click="markAsComplete"
                                        class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 font-medium text-sm flex-1 justify-center md:flex-initial">
                                        <x-heroicon-o-check class="w-4 h-4 mr-1" />
                                        Mark as Complete
                                    </button>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg font-medium text-sm flex-1 justify-center md:flex-initial">
                                        <x-heroicon-s-check-circle class="w-4 h-4 mr-1" />
                                        Completed
                                    </span>
                                @endif

                                <button 
                                    wire:click="goToNextLesson"
                                    class="inline-flex items-center justify-center p-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium text-sm"
                                    title="Next Lesson">
                                    <x-heroicon-o-chevron-right class="w-5 h-5" />
                                </button>
                            </div>
                        @else
                            {{-- Free Preview - Show "Purchase Course" CTA --}}
                            <div class="flex flex-col gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <x-heroicon-o-information-circle class="w-5 h-5 inline mr-1" />
                                    You're watching a free preview lesson. Purchase the course to access all lessons and track your progress.
                                </p>
                                <a href="{{ route('courses.show', $this->course->slug) }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium text-sm">
                                    <x-heroicon-o-shopping-cart class="w-5 h-5 mr-2" />
                                    Purchase Course
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- MOBILE: Curriculum Section (Order 3 - paling bawah) --}}
            <div class="w-full lg:hidden order-3 bg-white dark:bg-gray-800 rounded-xl shadow">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Course Curriculum</h3>
                </div>
                <div class="max-h-[50vh] overflow-y-auto">
                    @foreach($this->topics as $topic)
                        <div class="border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                            <div class="p-4 bg-gray-50 dark:bg-gray-900">
                                <h4 class="font-medium text-gray-900 dark:text-white text-sm">{{ $topic->title }}</h4>
                                @if($userOwnsCourse)
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        <span id="mobile-topic-progress-{{ $topic->id }}" data-base-duration="{{ $this->getTopicWatchedDuration($topic) - $this->getLessonWatchedSeconds($this->currentLesson) }}">{{ \App\Filament\User\Pages\Learn::formatDuration($this->getTopicWatchedDuration($topic)) }}</span> / {{ \App\Filament\User\Pages\Learn::formatDuration($this->getTopicTotalDuration($topic)) }}
                                    </p>
                                @endif
                            </div>
                            <ul>
                                @foreach($topic->lessons as $lesson)
                                    <li>
                                        <button 
                                            wire:click="selectLesson({{ $lesson->id }})"
                                            class="w-full flex items-center gap-3 p-3 text-left hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors
                                                {{ $this->currentLesson && $this->currentLesson->id === $lesson->id ? 'bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500' : '' }}">
                                            @if(in_array($lesson->id, $this->completedLessonIds))
                                                <x-heroicon-s-check-circle class="w-5 h-5 text-green-500 flex-shrink-0" />
                                            @else
                                                <x-heroicon-o-play-circle class="w-5 h-5 text-gray-400 flex-shrink-0" />
                                            @endif
                                            <div class="flex-1">
                                                <span class="text-sm block {{ $this->currentLesson && $this->currentLesson->id === $lesson->id ? 'font-medium text-amber-600 dark:text-amber-400' : 'text-gray-700 dark:text-gray-300' }}">
                                                    {{ $lesson->title }}
                                                </span>
                                                @if($userOwnsCourse && $lesson->duration)
                                                    @if($this->currentLesson && $this->currentLesson->id === $lesson->id)
                                                        <span class="text-xs text-gray-400"><span id="mobile-lesson-progress">{{ \App\Filament\User\Pages\Learn::formatDuration($this->getLessonWatchedSeconds($lesson)) }}</span> / {{ \App\Filament\User\Pages\Learn::formatDuration($lesson->duration) }}</span>
                                                    @else
                                                        <span class="text-xs text-gray-400">{{ \App\Filament\User\Pages\Learn::formatDuration($this->getLessonWatchedSeconds($lesson)) }} / {{ \App\Filament\User\Pages\Learn::formatDuration($lesson->duration) }}</span>
                                                    @endif
                                                @elseif(!$userOwnsCourse && $lesson->duration)
                                                    <span class="text-xs text-gray-400">{{ \App\Filament\User\Pages\Learn::formatDuration($lesson->duration) }}</span>
                                                @endif
                                            </div>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-12 text-center">
            <x-heroicon-o-exclamation-circle class="w-12 h-12 mx-auto text-gray-400 mb-4" />
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Course not found</h3>
            <p class="text-gray-500 dark:text-gray-400">The course you're looking for doesn't exist or you don't have access.</p>
        </div>
    @endif

    @push('scripts')
    <script src="https://cdn.plyr.io/3.8.4/plyr.polyfilled.js"></script>
    <script>

        var player;
        var saveInterval;
        var displayInterval;
        var lastSavedTime = 0;
        var currentLessonId = null;
        var pendingPlayerData = null;  // Store pending lesson data
        var isCreatingPlayer = false;  // Guard to prevent race conditions
        var activeDisplayIntervalId = null;  // Track the active display interval
        var isInitialLoad = true;  // Track if this is initial page load (not auto-play)

        // Show loading spinner (called on lesson change)
        function showLoadingSpinner() {
            var videoContainer = document.querySelector('.tutor-video-player');
            if (!videoContainer) return;
            
            // Remove existing spinner if any
            var existingSpinner = document.getElementById('learn-loading-spinner');
            if (existingSpinner) {
                existingSpinner.remove();
            }
            
            // Create new spinner
            var spinner = document.createElement('div');
            spinner.id = 'learn-loading-spinner';
            spinner.className = 'loading-spinner';
            spinner.setAttribute('aria-hidden', 'true');
            videoContainer.insertBefore(spinner, videoContainer.firstChild);
        }

        function initPlayer() {
            console.log('Plyr init, isCreatingPlayer:', isCreatingPlayer);
            
            // Hide loading spinner on initial load
            var existingSpinner = document.getElementById('learn-loading-spinner');
            if (existingSpinner) {
                existingSpinner.remove();
            }
            
            // Check if current lesson has video
            var playerEl = document.getElementById('youtube-player');
            if (playerEl) {
                var videoId = playerEl.getAttribute('data-video-id');
                if (!videoId || videoId === 'null' || videoId === '') {
                    console.log('No video on initial load, showing no-video message');
                    var noVideoEl = document.getElementById('no-video-message');
                    if (noVideoEl) {
                        noVideoEl.style.display = 'flex';
                    }
                    playerEl.style.display = 'none';
                    return;
                }
            }
            
            // Only create player on initial load, not during lesson changes
            if (!isCreatingPlayer && !player) {
                createPlayer();
            }
        }

        document.addEventListener('DOMContentLoaded', initPlayer);

        function createPlayer() {
            if (isCreatingPlayer) {
                console.log('Already creating player, skipping duplicate call');
                return;
            }
            
            isCreatingPlayer = true;
            var playerEl = document.getElementById('youtube-player');
            if (!playerEl) {
                console.log('No player element found');
                isCreatingPlayer = false;
                return;
            }
            
            // Use pending data if available, otherwise read from DOM
            var lessonId, videoId, startSeconds;
            if (pendingPlayerData) {
                lessonId = pendingPlayerData.lessonId;
                videoId = pendingPlayerData.videoId;
                startSeconds = pendingPlayerData.startSeconds;
                console.log('Using pending player data:', pendingPlayerData);
                // DON'T clear yet, keep for retry if needed
            } else {
                lessonId = playerEl.getAttribute('data-lesson-id');
                videoId = playerEl.getAttribute('data-video-id');
                startSeconds = parseInt(playerEl.getAttribute('data-start-seconds')) || 0;
                console.log('Reading from DOM - lesson:', lessonId, 'video:', videoId);
            }
            
            if (!videoId) {
                console.log('No video ID found');
                isCreatingPlayer = false;
                return;
            }
            
            console.log('Creating player for lesson', lessonId, 'video', videoId, 'start at', startSeconds);
            
            // Final cleanup before creating
            if (player) {
                try {
                    player.destroy();
                } catch(e) {
                    console.log('Final destroy error:', e);
                }
                player = null;
            }
            
            // Hide no-video message if visible
            var noVideoEl = document.getElementById('no-video-message');
            if (noVideoEl) {
                noVideoEl.style.display = 'none';
            }
            
            // Show and prepare youtube-player element
            playerEl.style.display = 'block';
            playerEl.innerHTML = '';
            currentLessonId = parseInt(lessonId);
            lastSavedTime = 0;  // Reset saved time for new player
            
            // Determine if we should autoplay (not initial load)
            var shouldAutoplay = !isInitialLoad;
            console.log('Should autoplay:', shouldAutoplay, 'isInitialLoad:', isInitialLoad);
            
            try {
                var src = "https://www.youtube.com/embed/" + videoId + "?iv_load_policy=3&modestbranding=1&playsinline=1&showinfo=0&rel=0&enablejsapi=1";
                if (startSeconds && startSeconds > 0) {
                    src += "&start=" + startSeconds;
                }

                playerEl.innerHTML = '<iframe src="' + src + '" allowfullscreen allow="autoplay; encrypted-media"></iframe>';

                // Create Plyr instance
                player = new Plyr(playerEl, {
                    controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'settings', 'pip', 'airplay', 'fullscreen'],
                    hideControls: true,
                    resetOnEnd: false,
                    keyboard: { focused: true, global: false },
                    clickToPlay: true,
                    autoplay: shouldAutoplay,
                    youtube: {
                        noCookie: false,
                        rel: 0,
                        showinfo: 0,
                        iv_load_policy: 3,
                        modestbranding: 1
                    }
                });

                player.on('ready', function() {
                    console.log('Player ready, setting time display');
                    // Remove loading spinner after 2 seconds delay (same as watch.blade.php)
                    setTimeout(function() {
                        var spinner = document.getElementById('learn-loading-spinner');
                        if (spinner) {
                            spinner.remove();
                        }
                    }, 1000);

                    if (startSeconds && startSeconds > 0) {
                        player.currentTime = startSeconds;
                    }

                    // Update display immediately when player is ready
                    setTimeout(function() {
                        updateDisplayProgress();
                        if (isInitialLoad) {
                            console.log('Initial load - autoplay was disabled');
                            isInitialLoad = false;  // Mark initial load as done
                        } else {
                            console.log('Transition load - autoplay should have started');
                        }
                    }, 100);
                });

                player.on('play', handlePlayerPlay);
                player.on('pause', handlePlayerPause);
                player.on('ended', handlePlayerEnded);

                // EXACT WordPress poster opacity control (tutor-front.js)
                player.on('play', function() {
                    var poster = document.querySelector('.plyr--youtube.plyr__poster-enabled .plyr__poster');
                    if (poster) {
                        poster.style.opacity = '0';
                    }
                });

                player.on('pause', function() {
                    var poster = document.querySelector('.plyr--youtube.plyr__poster-enabled .plyr__poster');
                    if (poster) {
                        poster.style.opacity = '1';
                    }
                });
                
                // IMPORTANT: Track lesson ID on player object itself
                // This prevents issues with DOM attributes being reset by Livewire morphing
                player.currentLessonId = currentLessonId;
                player.currentVideoId = videoId;
                
                // Track if lesson is already completed (for rewatch feature)
                var isCompleted = playerContainer.getAttribute('data-is-completed') === 'true';
                player.isLessonCompleted = isCompleted;
                
                console.log('Player created successfully with video:', videoId, 'for lesson:', currentLessonId, 'isCompleted:', isCompleted);
                
                // Clear pending data after successful creation
                if (pendingPlayerData && pendingPlayerData.lessonId == lessonId) {
                    pendingPlayerData = null;
                }
            } catch(e) {
                console.log('Error creating player:', e);
            }
            
            isCreatingPlayer = false;
        }

        function formatDuration(seconds) {
            var hours = Math.floor(seconds / 3600);
            var minutes = Math.floor((seconds % 3600) / 60);
            var secs = seconds % 60;
            
            if (hours > 0) {
                return hours + ':' + String(minutes).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
            }
            return minutes + ':' + String(secs).padStart(2, '0');
        }

        function updateDisplayProgress() {
            // Only update if the current player is still the active one
            // This prevents old display intervals from overwriting current player display
            if (!player || typeof player.currentTime !== 'number') {
                return;
            }
            
            var currentTime = Math.floor(player.currentTime);
            
            // Update main area progress
            var mainProgressEl = document.getElementById('current-lesson-progress');
            if (mainProgressEl) {
                mainProgressEl.textContent = formatDuration(currentTime);
            }
            
            // Update sidebar lesson progress
            var sidebarLessonProgressEl = document.getElementById('sidebar-lesson-progress');
            if (sidebarLessonProgressEl) {
                sidebarLessonProgressEl.textContent = formatDuration(currentTime);
            }
            
            // Update mobile curriculum lesson progress
            var mobileLessonProgressEl = document.getElementById('mobile-lesson-progress');
            if (mobileLessonProgressEl) {
                mobileLessonProgressEl.textContent = formatDuration(currentTime);
            }
            
            // Update sidebar topic progress (add current lesson duration to existing)
            var topicProgressEl = document.querySelector('[id^="sidebar-topic-progress-"]');
            if (topicProgressEl) {
                var baseDuration = parseInt(topicProgressEl.getAttribute('data-base-duration')) || 0;
                topicProgressEl.textContent = formatDuration(baseDuration + currentTime);
            }
            
            // Update mobile curriculum topic progress (add current lesson duration to existing)
            var mobileTopicProgressEl = document.querySelector('[id^="mobile-topic-progress-"]');
            if (mobileTopicProgressEl) {
                var baseDuration = parseInt(mobileTopicProgressEl.getAttribute('data-base-duration')) || 0;
                mobileTopicProgressEl.textContent = formatDuration(baseDuration + currentTime);
            }
            
            // Update sidebar course progress (time display)
            var courseProgressEl = document.getElementById('sidebar-course-progress');
            if (courseProgressEl) {
                var baseDuration = parseInt(courseProgressEl.getAttribute('data-base-duration')) || 0;
                var totalDuration = parseInt(courseProgressEl.getAttribute('data-total-duration')) || 1;
                var totalWatched = baseDuration + currentTime;
                courseProgressEl.textContent = formatDuration(totalWatched);
                
                // Update course progress percentage
                var coursePercentEl = document.getElementById('sidebar-course-percent');
                if (coursePercentEl) {
                    var percent = Math.min(100, ((totalWatched / totalDuration) * 100)).toFixed(1);
                    coursePercentEl.textContent = percent + '% complete';
                }
                
                // Update course progress bar
                var courseProgressBarEl = document.getElementById('sidebar-course-progress-bar');
                if (courseProgressBarEl) {
                    var percent = Math.min(100, ((totalWatched / totalDuration) * 100));
                    courseProgressBarEl.style.width = percent + '%';
                }
            }
            
            // Update MOBILE course progress (time display)
            var mobileProgressEl = document.getElementById('mobile-course-progress');
            if (mobileProgressEl) {
                var baseDuration = parseInt(mobileProgressEl.getAttribute('data-base-duration')) || 0;
                var totalDuration = parseInt(mobileProgressEl.getAttribute('data-total-duration')) || 1;
                var totalWatched = baseDuration + currentTime;
                mobileProgressEl.textContent = formatDuration(totalWatched);
                
                // Update mobile course progress percentage
                var mobilePercentEl = document.getElementById('mobile-course-percent');
                if (mobilePercentEl) {
                    var percent = Math.min(100, ((totalWatched / totalDuration) * 100)).toFixed(1);
                    mobilePercentEl.textContent = percent + '% complete';
                }
                
                // Update mobile course progress bar
                var mobileProgressBarEl = document.getElementById('mobile-course-progress-bar');
                if (mobileProgressBarEl) {
                    var percent = Math.min(100, ((totalWatched / totalDuration) * 100));
                    mobileProgressBarEl.style.width = percent + '%';
                }
            }
        }

        function handlePlayerPlay() {
            console.log('Player playing for lesson', currentLessonId);
            
            // Mark initial load as done - we're now actively using the player
            // This handles case where event listeners register after auto-advance
            if (isInitialLoad) {
                console.log('First play detected - marking initial load complete');
                isInitialLoad = false;
            }
            
            // Clear existing intervals first
            if (displayInterval) {
                clearInterval(displayInterval);
                displayInterval = null;
            }
            if (saveInterval) {
                clearInterval(saveInterval);
                saveInterval = null;
            }
            
            // Update display every second
            displayInterval = setInterval(function() {
                updateDisplayProgress();
            }, 1000);
            
            // Save to server every 5 seconds
            saveInterval = setInterval(saveProgress, 5000);
        }

        function handlePlayerPause() {
            console.log('Player paused');
            saveProgress();
            if (saveInterval) {
                clearInterval(saveInterval);
                saveInterval = null;
            }
            if (displayInterval) {
                clearInterval(displayInterval);
                displayInterval = null;
            }
        }

        function handlePlayerEnded() {
            console.log('Player ended for lesson', currentLessonId);
            
            // IMPORTANT: Guard against old player emitting ENDED event
            // Use player object's tracked lessonId (more reliable than DOM which Livewire can reset)
            if (!player || !player.currentLessonId) {
                console.log('Player not properly initialized, ignoring ENDED');
                return;
            }
            
            if (currentLessonId !== player.currentLessonId) {
                console.log('ENDED event from old player. Current lesson:', currentLessonId, 'Player tracked lesson:', player.currentLessonId);
                return;
            }
            
            // Save final progress
            saveProgress();
            
            // Clear intervals
            if (saveInterval) {
                clearInterval(saveInterval);
                saveInterval = null;
            }
            if (displayInterval) {
                clearInterval(displayInterval);
                displayInterval = null;
            }
            
            // Check if lesson is already completed - if so, don't auto-advance
            // This allows rewatching completed videos
            var isAlreadyCompleted = player.isLessonCompleted || false;
            
            if (isAlreadyCompleted) {
                console.log('Lesson already completed - allowing rewatch, no auto-advance');
                // Reset video to start for easy replay
                if (player && typeof player.restart === 'function') {
                    // Don't auto-restart, just leave it at end so user can click play again
                }
                return;
            }
            
            console.log('Processing ENDED event - auto-marking complete and advancing...');
            
            // Mark lesson as complete and auto-advance to next lesson
            // This will also dispatch event to switch player
            if (typeof Livewire !== 'undefined') {
                @this.call('markAsComplete');
            } else {
                console.log('Livewire not ready yet');
            }
        }

        function saveProgress() {
            if (player && typeof player.currentTime === 'number') {
                var currentTime = Math.floor(player.currentTime);
                // Save if time changed and is greater than 0, or if more than 2 seconds have passed since last save
                if (currentTime > 0 && (currentTime !== lastSavedTime || currentTime - lastSavedTime >= 2)) {
                    lastSavedTime = currentTime;
                    console.log('Saving progress: lesson', currentLessonId, 'time', currentTime, 'seconds');
                    @this.call('saveProgress', currentTime);
                }
            }
        }

        // Save progress when user leaves page
        window.addEventListener('beforeunload', function() {
            saveProgress();
        });

        // Save progress when user pauses/ends playback
        window.addEventListener('beforeunload', function() {
            saveProgress();
        });

        // Listen for lesson change event from Livewire selectLesson() method
        function handleLessonChange(data) {
            console.log('Handling lesson change:', data);
            console.log('VideoId type:', typeof data.videoId, 'value:', data.videoId, 'isEmpty:', !data.videoId);
            
            // Mark that we're no longer on initial load
            // This enables auto-play for subsequent lessons
            isInitialLoad = false;
            
            // Show loading spinner for new lesson
            showLoadingSpinner();
            
            // FIRST: Save the current video progress before switching
            // IMPORTANT: Use currentLessonId which is the PREVIOUS lesson, not the new one
            if (player && typeof player.currentTime === 'number' && currentLessonId !== null) {
                try {
                    var currentTime = Math.floor(player.currentTime);
                    if (currentTime > 0) {
                        console.log('Auto-saving previous video (lesson', currentLessonId, ') progress:', currentTime, 'seconds');
                        // Pass lessonId to saveProgress so it saves to the correct lesson
                        @this.call('saveProgressWithLesson', currentLessonId, currentTime);
                    }
                } catch(e) {
                    console.log('Error saving previous progress:', e);
                }
            }
            
            // If no video ID (null, undefined, empty string, or 'null' string), clear player and show no-video message
            if (!data.videoId || data.videoId === 'null' || data.videoId === '' || data.videoId === null || data.videoId === undefined) {
                console.log('No video for this lesson (videoId:', data.videoId, '), showing no-video message');
                
                // Stop and destroy player
                if (player && typeof player.pause === 'function') {
                    try {
                        player.pause();
                    } catch(e) {}
                }
                
                // Clear intervals
                if (saveInterval) {
                    clearInterval(saveInterval);
                    saveInterval = null;
                }
                if (displayInterval) {
                    clearInterval(displayInterval);
                    displayInterval = null;
                }
                
                // Destroy player
                if (player) {
                    try {
                        if (typeof player.destroy === 'function') {
                            player.destroy();
                        }
                    } catch(e) {
                        console.log('Error destroying player:', e);
                    }
                    player = null;
                }
                
                // Clear youtube-player element
                var playerEl = document.getElementById('youtube-player');
                if (playerEl) {
                    playerEl.innerHTML = '';
                    playerEl.style.display = 'none';
                }
                
                // Show no-video message
                var noVideoEl = document.getElementById('no-video-message');
                if (noVideoEl) {
                    noVideoEl.style.display = 'flex';
                    console.log('No-video message displayed');
                    
                    // Remove spinner when showing no-video message
                    var spinner = document.getElementById('learn-loading-spinner');
                    if (spinner) {
                        spinner.remove();
                    }
                } else {
                    console.log('ERROR: no-video-message element not found!');
                }
                
                // Reset state
                currentLessonId = null;
                lastSavedTime = 0;
                pendingPlayerData = null;
                
                console.log('Player cleared for lesson without video');
                return;
            }
            
            // Store the data before any async operations
            // This prevents Livewire morphing from overwriting it
            pendingPlayerData = {
                lessonId: data.lessonId,
                videoId: data.videoId,
                startSeconds: data.startSeconds
            };
            
            var playerEl = document.getElementById('youtube-player');
            if (!playerEl) {
                console.log('Player element not found');
                return;
            }
            
            // Stop any playing video first
            if (player && typeof player.pause === 'function') {
                try {
                    player.pause();
                    console.log('Stopped current video');
                } catch(e) {
                    console.log('Error stopping video:', e);
                }
            }
            
            // Clear ALL intervals BEFORE destroying player
            // This is critical to prevent old intervals from running
            console.log('Clearing all intervals...');
            if (saveInterval) {
                clearInterval(saveInterval);
                saveInterval = null;
            }
            if (displayInterval) {
                clearInterval(displayInterval);
                displayInterval = null;
            }
            
            // Destroy existing player aggressively
            if (player) {
                try {
                    // Call destroy multiple times if needed
                    if (typeof player.destroy === 'function') {
                        player.destroy();
                        console.log('Player destroyed');
                    }
                } catch(e) {
                    console.log('Error destroying player:', e);
                }
                player = null;
            }
            
            // Clear the player div completely and remove all iframes
            playerEl.innerHTML = '';
            
            // Remove any leftover iframes
            var iframes = playerEl.querySelectorAll('iframe');
            iframes.forEach(iframe => {
                iframe.remove();
            });
            
            // Reset state
            currentLessonId = null;
            lastSavedTime = 0;
            
            // Update attributes (for reference, but we use pendingPlayerData)
            playerEl.setAttribute('data-lesson-id', data.lessonId);
            playerEl.setAttribute('data-video-id', data.videoId);
            playerEl.setAttribute('data-start-seconds', data.startSeconds);
            
            // Create new player with pending data stored
            setTimeout(() => {
                console.log('Creating new player with pending data:', pendingPlayerData);
                createPlayer();
            }, 500);
        }
        
        if (typeof Livewire !== 'undefined') {
            Livewire.on('lesson-changed', handleLessonChange);
        } else {
            // Fallback: Check periodically if Livewire becomes available
            var checkLivewireInterval = setInterval(() => {
                if (typeof Livewire !== 'undefined') {
                    clearInterval(checkLivewireInterval);
                    console.log('Livewire loaded, registering lesson-changed event');
                    Livewire.on('lesson-changed', handleLessonChange);
                }
            }, 100);
        }
        
        // Also use mutation observer as backup for direct attribute changes
        var lessonChangeObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'data-lesson-id') {
                    var playerEl = document.getElementById('youtube-player');
                    if (playerEl) {
                        var newLessonId = parseInt(playerEl.getAttribute('data-lesson-id'));
                        
                        // If lesson ID changed, destroy and recreate player
                        if (currentLessonId !== null && currentLessonId !== newLessonId) {
                            console.log('Lesson changed via observer from', currentLessonId, 'to', newLessonId);
                            if (player) {
                                try {
                                    player.destroy();
                                } catch(e) {
                                    console.log('Player destroy error:', e);
                                }
                                player = null;
                            }
                            lastSavedTime = 0;
                            
                            setTimeout(function() {
                                createPlayer();
                            }, 100);
                        }
                    }
                }
            });
        });
        
        // Start observing player element for attribute changes
        var playerEl = document.getElementById('youtube-player');
        if (playerEl) {
            lessonChangeObserver.observe(playerEl, {
                attributes: true,
                attributeFilter: ['data-lesson-id', 'data-video-id', 'data-start-seconds']
            });
        }
        
        // Also listen to Livewire navigated for safety
        document.addEventListener('livewire:navigated', function() {
            var playerEl = document.getElementById('youtube-player');
            if (playerEl && !player) {
                setTimeout(createPlayer, 100);
            }
        });

        // Helper function to show "No video available" message
        function renderNoVideoMessage() {
            console.log('Showing no-video message');
            
            // Hide youtube-player
            var playerEl = document.getElementById('youtube-player');
            if (playerEl) {
                playerEl.style.display = 'none';
            }
            
            // Show no-video message
            var noVideoEl = document.getElementById('no-video-message');
            if (noVideoEl) {
                noVideoEl.style.display = 'flex';
                console.log('No-video message now visible');
            }
        }
        
        // Listen to Livewire morph/finish to handle no-video message persistence
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', ({ el, component }) => {
                console.log('Livewire morph.updated triggered');
                // After morphing, check if we should show no-video message
                var playerEl = document.getElementById('youtube-player');
                if (playerEl) {
                    var videoId = playerEl.getAttribute('data-video-id');
                    console.log('After morph: videoId =', videoId, 'isEmpty:', !videoId || videoId === 'null' || videoId === '');
                    if (!videoId || videoId === 'null' || videoId === '') {
                        console.log('No video after morph, rendering message immediately');
                        renderNoVideoMessage();
                    }
                }
            });
            
            // Also listen to before morph to see if we can prevent the issue
            Livewire.hook('morph.removing', ({ el, component }) => {
                var playerEl = document.getElementById('youtube-player');
                if (playerEl && !player) {  // If no player instance, probably no-video lesson
                    console.log('Storing no-video state before morph');
                    playerEl.setAttribute('data-no-video', 'true');
                }
            });
        }
    </script>
    @endpush
</x-filament-panels::page>
