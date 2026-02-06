<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($courses as $item)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                {{-- Thumbnail --}}
                @if($item['course']->thumbnail)
                    <img src="{{ Storage::url($item['course']->thumbnail) }}" 
                         alt="{{ $item['course']->title }}" 
                         class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                        <x-heroicon-o-academic-cap class="w-12 h-12 text-white" />
                    </div>
                @endif

                <div class="p-6">
                    {{-- Category Badge --}}
                    @if($item['course']->category)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 mb-2">
                            {{ $item['course']->category->name }}
                        </span>
                    @endif

                    {{-- Title --}}
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                        {{ $item['course']->title }}
                    </h3>

                    {{-- Progress --}}
                    <div class="mb-4">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full transition-all duration-300" style="width: {{ $item['progress'] }}%"></div>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs font-medium text-gray-900 dark:text-white">{{ $item['progress'] }}% complete</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ \App\Filament\User\Pages\MyCourses::formatDuration($item['watchedSeconds']) }} / {{ \App\Filament\User\Pages\MyCourses::formatDuration($item['totalDuration']) }} <span class="inline-block mx-2 text-lg text-amber-500">•</span> {{ $item['completedLessons'] }}/{{ $item['totalLessons'] }} lessons</span>
                        </div>
                    </div>

                    {{-- Purchased Date --}}
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <x-heroicon-o-calendar class="w-4 h-4 inline mr-1" />
                        Purchased {{ $item['purchasedAt']?->format('d M Y') ?? 'N/A' }}
                    </p>

                    {{-- Action Button --}}
                    <a href="{{ url('/dashboard/learn/' . $item['course']->slug) }}" 
                       class="block w-full text-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium transition-colors">
                        @if($item['progress'] == 0)
                            Start Learning
                        @elseif($item['progress'] == 100)
                            Review Course
                        @else
                            Continue Learning
                        @endif
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-12 text-center">
                    <x-heroicon-o-academic-cap class="w-12 h-12 mx-auto text-gray-400 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No courses yet</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Start your learning journey today!</p>
                    <a href="/courses" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium">
                        Browse Courses
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
