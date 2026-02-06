<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        {{-- Stats Cards --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-full">
                    <x-heroicon-o-academic-cap class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">My Courses</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $coursesCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <x-heroicon-o-check-circle class="w-6 h-6 text-green-600 dark:text-green-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Lessons Completed</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $completedLessons }}/{{ $totalLessons }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <x-heroicon-o-chart-bar class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Overall Progress</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $overallProgress }}%</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Continue Learning --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Continue Learning</h3>
            </div>
            <div class="p-6">
                @forelse($coursesWithProgress as $courseData)
                    <div class="mb-4 last:mb-0">
                        {{-- Desktop Layout --}}
                        <div class="hidden md:flex items-center space-x-4">
                            @if($courseData['course']->thumbnail)
                                <img src="{{ Storage::url($courseData['course']->thumbnail) }}" alt="{{ $courseData['course']->title }}" class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <x-heroicon-o-academic-cap class="w-8 h-8 text-gray-400" />
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white truncate">{{ $courseData['course']->title }}</p>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                                    <div class="bg-amber-500 h-2 rounded-full transition-all duration-300" style="width: {{ $courseData['progress'] }}%"></div>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs font-medium text-gray-900 dark:text-white">{{ $courseData['progress'] }}% complete</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ \App\Filament\User\Pages\Dashboard::formatDuration($courseData['watchedSeconds']) }} / {{ \App\Filament\User\Pages\Dashboard::formatDuration($courseData['totalDuration']) }} <span class="inline-block mx-2 text-lg text-amber-500">•</span> {{ $courseData['completedLessons'] }}/{{ $courseData['totalLessons'] }} lessons</span>
                                </div>
                            </div>
                            <a href="{{ url('/dashboard/learn/' . $courseData['course']->slug) }}" 
                               class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 text-sm font-medium whitespace-nowrap">
                                Continue
                            </a>
                        </div>

                        {{-- Mobile Layout --}}
                        <div class="flex md:hidden flex-col space-y-3">
                            <div class="flex items-center space-x-4">
                                @if($courseData['course']->thumbnail)
                                    <img src="{{ Storage::url($courseData['course']->thumbnail) }}" alt="{{ $courseData['course']->title }}" class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                        <x-heroicon-o-academic-cap class="w-8 h-8 text-gray-400" />
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 dark:text-white truncate">{{ $courseData['course']->title }}</p>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                                        <div class="bg-amber-500 h-2 rounded-full transition-all duration-300" style="width: {{ $courseData['progress'] }}%"></div>
                                    </div>
                                    <div class="flex flex-col mt-1">
                                        <span class="text-xs font-medium text-gray-900 dark:text-white">{{ $courseData['progress'] }}% complete</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ \App\Filament\User\Pages\Dashboard::formatDuration($courseData['watchedSeconds']) }} / {{ \App\Filament\User\Pages\Dashboard::formatDuration($courseData['totalDuration']) }} <span class="inline-block mx-2 text-lg text-amber-500">•</span> {{ $courseData['completedLessons'] }}/{{ $courseData['totalLessons'] }} lessons</span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ url('/dashboard/learn/' . $courseData['course']->slug) }}" 
                               class="w-full mt-1.5 px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 text-sm font-medium text-center">
                                Continue
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No courses yet. Start learning today!</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h3>
            </div>
            <div class="p-6">
                @forelse($recentOrders as $order)
                    <div class="flex items-center justify-between mb-4 last:mb-0">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($order->status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No orders yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>
