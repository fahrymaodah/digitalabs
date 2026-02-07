<x-filament-panels::page>
    {{-- KPI Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-x-3">
                <div class="flex w-12 h-12 items-center justify-center rounded-lg bg-primary-50 dark:bg-primary-400/10">
                    <x-heroicon-o-users class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Visitors</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">1,234</p>
                    <p class="text-xs text-green-600 dark:text-green-400">+12% dari minggu lalu</p>
                </div>
            </div>
        </div>

        <div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-x-3">
                <div class="flex w-12 h-12 items-center justify-center rounded-lg bg-success-50 dark:bg-success-400/10">
                    <x-heroicon-o-eye class="w-6 h-6 text-success-600 dark:text-success-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Page Views</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">5,678</p>
                    <p class="text-xs text-green-600 dark:text-green-400">+8% dari minggu lalu</p>
                </div>
            </div>
        </div>

        <div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-x-3">
                <div class="flex w-12 h-12 items-center justify-center rounded-lg bg-warning-50 dark:bg-warning-400/10">
                    <x-heroicon-o-clock class="w-6 h-6 text-warning-600 dark:text-warning-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg. Duration</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">3:24</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">menit per sesi</p>
                </div>
            </div>
        </div>

        <div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center gap-x-3">
                <div class="flex w-12 h-12 items-center justify-center rounded-lg bg-danger-50 dark:bg-danger-400/10">
                    <x-heroicon-o-arrow-trending-down class="w-6 h-6 text-danger-600 dark:text-danger-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bounce Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">42.5%</p>
                    <p class="text-xs text-green-600 dark:text-green-400">-5% dari minggu lalu</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Banner --}}
    {{-- <x-filament::section class="mb-6">
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-information-circle class="w-5 h-5 text-warning-500" />
                <span>Mode Demo</span>
            </div>
        </x-slot>
        <x-slot name="description">
            Data yang ditampilkan adalah data contoh. Data real dari Google Analytics akan muncul dalam 24-48 jam.
        </x-slot>
    </x-filament::section> --}}

    {{-- Traffic Overview Chart --}}
    <x-filament::section class="mb-6">
        <x-slot name="heading">Traffic Overview</x-slot>
        <x-slot name="description">Tren pengunjung 30 hari terakhir</x-slot>
        <div class="h-80">
            <canvas id="trafficChart"></canvas>
        </div>
    </x-filament::section>

    {{-- Device Distribution & Traffic Sources (2 columns) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Device Distribution with Percentages --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-device-phone-mobile class="w-5 h-5 text-primary-600" />
                    <span>Device Distribution</span>
                </div>
            </x-slot>
            <x-slot name="description">Pengunjung berdasarkan perangkat</x-slot>
            
            {{-- Donut Chart --}}
            <div class="h-56 flex items-center justify-center mb-8">
                <canvas id="deviceChart"></canvas>
            </div>

            {{-- Device Stats with Percentages --}}
            <div class="space-y-3">
                @foreach($devicePercentages as $device)
                <div class="bg-gray-50 dark:bg-white/5 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            @if($device['device'] === 'Mobile')
                                <x-heroicon-o-device-phone-mobile class="w-5 h-5 text-primary-600" />
                            @elseif($device['device'] === 'Desktop')
                                <x-heroicon-o-computer-desktop class="w-5 h-5 text-success-600" />
                            @else
                                <x-heroicon-o-device-tablet class="w-5 h-5 text-warning-600" />
                            @endif
                            <span class="font-medium text-gray-900 dark:text-white text-sm">{{ $device['device'] }}</span>
                        </div>
                        <span class="text-lg font-bold text-primary-600 dark:text-primary-400">{{ $device['percentage'] }}%</span>
                    </div>
                    <div class="h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-primary-500 to-primary-600" style="width: {{ $device['percentage'] }}%"></div>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                        {{ number_format($device['count']) }} users
                    </p>
                </div>
                @endforeach
            </div>
        </x-filament::section>

        {{-- Traffic Sources --}}
        <x-filament::section>
            <x-slot name="heading">Traffic Sources</x-slot>
            <x-slot name="description">Dari mana pengunjung datang</x-slot>
            <div style="height: 550px;">
                <canvas id="trafficSourcesChart"></canvas>
            </div>
        </x-filament::section>
    </div>

    {{-- Top Pages & Peak Hours (2 columns) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Top Pages --}}
        <x-filament::section>
            <x-slot name="heading">Top 10 Pages</x-slot>
            <x-slot name="description">Halaman paling banyak dikunjungi</x-slot>
            <div id="topPagesContainer" class="h-96">
                <canvas id="topPagesChart"></canvas>
            </div>
        </x-filament::section>

        {{-- Peak Hours --}}
        <x-filament::section>
            <x-slot name="heading">Peak Hours</x-slot>
            <x-slot name="description">Jam dengan aktivitas tertinggi (7 hari terakhir)</x-slot>
            <div id="peakHoursContainer" class="h-96">
                <canvas id="peakHoursChart"></canvas>
            </div>
        </x-filament::section>
    </div>

    {{-- Tables: Location & Browser --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-filament::section>
            <x-slot name="heading">Top Locations</x-slot>
            <x-slot name="description">Kota dengan pengunjung terbanyak</x-slot>
            
            @if(count($geographicData) > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-400">Kota</th>
                            <th class="py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-400">Negara</th>
                            <th class="py-3 text-right text-sm font-semibold text-gray-600 dark:text-gray-400">Users</th>
                            <th class="py-3 text-right text-sm font-semibold text-gray-600 dark:text-gray-400">Sessions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($geographicData as $index => $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary-100 dark:bg-primary-400/10 text-xs font-bold text-primary-600 dark:text-primary-400">{{ $index + 1 }}</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $row['city'] }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-sm text-gray-500 dark:text-gray-400">{{ $row['country'] }}</td>
                            <td class="py-3 text-right">
                                <span class="font-bold text-primary-600 dark:text-primary-400">{{ number_format($row['users']) }}</span>
                            </td>
                            <td class="py-3 text-right text-sm text-gray-500 dark:text-gray-400">{{ number_format($row['sessions']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <x-heroicon-o-map-pin class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                <p>Belum ada data lokasi</p>
            </div>
            @endif
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Browser & OS</x-slot>
            <x-slot name="description">Platform yang digunakan pengunjung</x-slot>
            
            @if(count($browserData) > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-400">Browser</th>
                            <th class="py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-400">OS</th>
                            <th class="py-3 text-right text-sm font-semibold text-gray-600 dark:text-gray-400">Users</th>
                            <th class="py-3 text-right text-sm font-semibold text-gray-600 dark:text-gray-400">Bounce</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($browserData as $row)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $row['browser'] }}</span>
                            </td>
                            <td class="py-3 text-sm text-gray-500 dark:text-gray-400">{{ $row['os'] }}</td>
                            <td class="py-3 text-right">
                                <span class="font-bold text-primary-600 dark:text-primary-400">{{ number_format($row['users']) }}</span>
                            </td>
                            <td class="py-3 text-right">
                                @php
                                    $bounceValue = floatval(str_replace('%', '', $row['bounceRate']));
                                @endphp
                                <x-filament::badge :color="$bounceValue > 50 ? 'danger' : 'success'">
                                    {{ $row['bounceRate'] }}
                                </x-filament::badge>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <x-heroicon-o-computer-desktop class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                <p>Belum ada data browser</p>
            </div>
            @endif
        </x-filament::section>
    </div>

    {{-- === NEW SECTIONS === --}}

    {{-- 1. Conversion Tracking --}}
    <x-filament::section class="mb-6 mt-8">
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-cursor-arrow-ripple class="w-6 h-6 text-success-600" />
                <span>Conversion Tracking</span>
            </div>
        </x-slot>
        <x-slot name="description">Konversi dan funnel pengunjung</x-slot>
        
        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-success-50 to-success-100 dark:from-success-900/20 dark:to-success-800/20 rounded-lg p-4 border border-success-200 dark:border-success-800">
                <p class="text-xs font-medium text-success-700 dark:text-success-400 mb-1">Total Conversions</p>
                <p class="text-2xl font-bold text-success-900 dark:text-success-300">{{ number_format($conversionData['total_conversions']) }}</p>
                <p class="text-xs text-success-600 dark:text-success-500 mt-1">This month</p>
            </div>
            <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-lg p-4 border border-primary-200 dark:border-primary-800">
                <p class="text-xs font-medium text-primary-700 dark:text-primary-400 mb-1">Conversion Rate</p>
                <p class="text-2xl font-bold text-primary-900 dark:text-primary-300">{{ $conversionData['conversion_rate'] }}%</p>
                <p class="text-xs text-primary-600 dark:text-primary-500 mt-1">Of all visitors</p>
            </div>
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 rounded-lg p-4 border border-amber-200 dark:border-amber-800">
                <p class="text-xs font-medium text-amber-700 dark:text-amber-400 mb-1">Total Revenue</p>
                <p class="text-2xl font-bold text-amber-900 dark:text-amber-300">Rp {{ number_format($conversionData['total_value'] / 1000000, 1) }}M</p>
                <p class="text-xs text-amber-600 dark:text-amber-500 mt-1">From conversions</p>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg p-4 border border-purple-200 dark:border-purple-800">
                <p class="text-xs font-medium text-purple-700 dark:text-purple-400 mb-1">Avg Order Value</p>
                <p class="text-2xl font-bold text-purple-900 dark:text-purple-300">Rp {{ number_format($conversionData['avg_order_value'] / 1000, 0) }}K</p>
                <p class="text-xs text-purple-600 dark:text-purple-500 mt-1">Per transaction</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Conversion by Type Table --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Conversions by Type</h4>
                <div class="space-y-3">
                    @foreach($conversionData['by_type'] as $conv)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-white/5 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $conv['type'] }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ number_format($conv['count']) }} conversions 
                                @if($conv['value'] > 0)
                                    · Rp {{ number_format($conv['value'] / 1000000, 1) }}M
                                @endif
                            </p>
                        </div>
                        <x-filament::badge :color="floatval(str_replace('%', '', $conv['rate'])) > 5 ? 'success' : 'warning'">
                            {{ $conv['rate'] }}
                        </x-filament::badge>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Conversion Funnel --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Conversion Funnel</h4>
                <div class="space-y-2">
                    @foreach($conversionData['funnel'] as $index => $stage)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $stage['stage'] }}</span>
                            <span class="text-gray-600 dark:text-gray-400">{{ number_format($stage['count']) }} ({{ $stage['percentage'] }}%)</span>
                        </div>
                        <div class="relative h-8 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-primary-500 to-primary-600 transition-all duration-500" 
                                 style="width: {{ $stage['percentage'] }}%">
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white">
                                {{ $stage['percentage'] }}%
                            </div>
                        </div>
                        @if($index < count($conversionData['funnel']) - 1)
                        <div class="flex justify-center my-1">
                            <x-heroicon-o-chevron-down class="w-4 h-4 text-gray-400" />
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-filament::section>

    {{-- 2. Landing Pages Performance --}}
    <x-filament::section class="mb-6">
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-rocket-launch class="w-6 h-6 text-primary-600" />
                <span>Landing Pages Performance</span>
            </div>
        </x-slot>
        <x-slot name="description">Performa halaman landing untuk konversi</x-slot>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="py-3 px-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-400">Landing Page</th>
                        <th class="py-3 px-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-400">Sessions</th>
                        <th class="py-3 px-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-400">Bounce Rate</th>
                        <th class="py-3 px-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-400">Avg Duration</th>
                        <th class="py-3 px-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-400">Conversions</th>
                        <th class="py-3 px-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-400">Conv. Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($landingPagesData as $page)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-document-text class="w-4 h-4 text-gray-400 flex-shrink-0" />
                                <span class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ $page['page'] }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-right text-sm font-bold text-primary-600 dark:text-primary-400">
                            {{ number_format($page['sessions']) }}
                        </td>
                        <td class="py-3 px-2 text-right text-sm">
                            <x-filament::badge :color="$page['bounceRate'] > 50 ? 'danger' : ($page['bounceRate'] > 40 ? 'warning' : 'success')">
                                {{ number_format($page['bounceRate'], 1) }}%
                            </x-filament::badge>
                        </td>
                        <td class="py-3 px-2 text-right text-sm text-gray-600 dark:text-gray-400">
                            {{ $page['avgDuration'] }}
                        </td>
                        <td class="py-3 px-2 text-right text-sm font-bold text-success-600 dark:text-success-400">
                            {{ $page['conversions'] }}
                        </td>
                        <td class="py-3 px-2 text-right text-sm">
                            <x-filament::badge :color="$page['conversionRate'] > 3 ? 'success' : 'warning'">
                                {{ number_format($page['conversionRate'], 2) }}%
                            </x-filament::badge>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::section>

    {{-- 3. Exit Pages --}}
    <x-filament::section class="mb-6">
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-arrow-right-on-rectangle class="w-6 h-6 text-danger-600" />
                <span>Exit Pages (Problem Areas)</span>
            </div>
        </x-slot>
        <x-slot name="description">Halaman dimana pengunjung paling sering keluar</x-slot>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="py-3 px-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-400">Exit Page</th>
                        <th class="py-3 px-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-400">Total Exits</th>
                        <th class="py-3 px-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-400">Exit Rate</th>
                        <th class="py-3 px-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-400">Page Views</th>
                        <th class="py-3 px-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-400">Avg Time Before Exit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($exitPagesData as $page)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-2">
                                @if($page['exitRate'] > 60)
                                    <x-heroicon-o-exclamation-triangle class="w-4 h-4 text-danger-500 flex-shrink-0" />
                                @else
                                    <x-heroicon-o-arrow-right-circle class="w-4 h-4 text-gray-400 flex-shrink-0" />
                                @endif
                                <span class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ $page['page'] }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-right text-sm font-bold text-gray-900 dark:text-white">
                            {{ number_format($page['exits']) }}
                        </td>
                        <td class="py-3 px-2 text-right text-sm">
                            <x-filament::badge :color="$page['exitRate'] > 60 ? 'danger' : ($page['exitRate'] > 45 ? 'warning' : 'success')">
                                {{ number_format($page['exitRate'], 1) }}%
                            </x-filament::badge>
                        </td>
                        <td class="py-3 px-2 text-right text-sm text-gray-600 dark:text-gray-400">
                            {{ number_format($page['pageViews']) }}
                        </td>
                        <td class="py-3 px-2 text-right text-sm text-gray-600 dark:text-gray-400">
                            {{ $page['avgTimeBeforeExit'] }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::section>

    {{-- 4. Demographics & Interests --}}
    <x-filament::section class="mb-6">
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-user-group class="w-6 h-6 text-purple-600" />
                <span>Audience Demographics</span>
            </div>
        </x-slot>
        <x-slot name="description">Profil demografi pengunjung untuk targeting ads</x-slot>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Age Distribution --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                    <x-heroicon-o-calendar class="w-4 h-4" />
                    Age Distribution
                </h4>
                <div class="space-y-2">
                    @foreach($demographicsData['age'] as $age)
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $age['range'] }}</span>
                            <span class="text-gray-600 dark:text-gray-400">{{ number_format($age['users']) }} ({{ $age['percentage'] }}%)</span>
                        </div>
                        <div class="relative h-6 bg-gray-200 dark:bg-gray-700 rounded overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-purple-600" style="width: {{ $age['percentage'] }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $age['conversions'] }} conversions</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Gender Distribution --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                    <x-heroicon-o-users class="w-4 h-4" />
                    Gender Distribution
                </h4>
                <div class="space-y-4">
                    @foreach($demographicsData['gender'] as $gender)
                    <div class="bg-gray-50 dark:bg-white/5 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $gender['gender'] }}</span>
                            <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $gender['percentage'] }}%</span>
                        </div>
                        <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-primary-500 to-primary-600" style="width: {{ $gender['percentage'] }}%"></div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
                            {{ number_format($gender['users']) }} users · {{ $gender['conversions'] }} conversions
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Interests --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                    <x-heroicon-o-heart class="w-4 h-4" />
                    Top Interests
                </h4>
                <div class="space-y-2">
                    @foreach($demographicsData['interests'] as $interest)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-white/5 rounded-lg">
                        <div class="flex-1">
                            <p class="font-medium text-sm text-gray-900 dark:text-white">{{ $interest['category'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($interest['users']) }} users</p>
                        </div>
                        <x-filament::badge :color="$interest['affinity'] === 'High' ? 'success' : ($interest['affinity'] === 'Medium' ? 'warning' : 'gray')">
                            {{ $interest['affinity'] }}
                        </x-filament::badge>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-filament::section>

    {{-- 5. User Acquisition Cost --}}
    <x-filament::section class="mb-6">
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-currency-dollar class="w-6 h-6 text-success-600" />
                <span>User Acquisition Cost Analysis</span>
            </div>
        </x-slot>
        <x-slot name="description">Biaya akuisisi per channel untuk optimasi budget ads</x-slot>
        
        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Total Spent</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($acquisitionCostData['summary']['total_spent'] / 1000000, 1) }}M</p>
            </div>
            <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-lg p-4 border border-primary-200 dark:border-primary-800">
                <p class="text-xs font-medium text-primary-700 dark:text-primary-400 mb-1">Total Conversions</p>
                <p class="text-2xl font-bold text-primary-900 dark:text-primary-300">{{ $acquisitionCostData['summary']['total_conversions'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-danger-50 to-danger-100 dark:from-danger-900/20 dark:to-danger-800/20 rounded-lg p-4 border border-danger-200 dark:border-danger-800">
                <p class="text-xs font-medium text-danger-700 dark:text-danger-400 mb-1">Avg CPA</p>
                <p class="text-2xl font-bold text-danger-900 dark:text-danger-300">Rp {{ number_format($acquisitionCostData['summary']['avg_cpa'] / 1000, 0) }}K</p>
            </div>
            <div class="bg-gradient-to-br from-success-50 to-success-100 dark:from-success-900/20 dark:to-success-800/20 rounded-lg p-4 border border-success-200 dark:border-success-800">
                <p class="text-xs font-medium text-success-700 dark:text-success-400 mb-1">ROAS</p>
                <p class="text-2xl font-bold text-success-900 dark:text-success-300">{{ $acquisitionCostData['summary']['roas'] }}x</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- By Channel Table --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Cost by Channel</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-400">Channel</th>
                                <th class="py-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-400">CPA</th>
                                <th class="py-2 text-right text-xs font-semibold text-gray-600 dark:text-gray-400">ROAS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($acquisitionCostData['by_channel'] as $channel)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="py-3">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $channel['channel'] }}</p>
                                    <p class="text-xs text-gray-500">{{ number_format($channel['clicks']) }} clicks · Rp {{ number_format($channel['cpc']) }} CPC</p>
                                </td>
                                <td class="py-3 text-right">
                                    <p class="font-bold text-danger-600 dark:text-danger-400">Rp {{ number_format($channel['cpa'] / 1000, 0) }}K</p>
                                    <p class="text-xs text-gray-500">{{ $channel['conversions'] }} conv</p>
                                </td>
                                <td class="py-3 text-right">
                                    <x-filament::badge :color="$channel['roas'] >= 3 ? 'success' : ($channel['roas'] >= 2 ? 'warning' : 'danger')">
                                        {{ $channel['roas'] }}x
                                    </x-filament::badge>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Performance Trend Chart --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Performance Trend (4 Weeks)</h4>
                <div class="h-64">
                    <canvas id="acquisitionTrendChart"></canvas>
                </div>
            </div>
        </div>
    </x-filament::section>

    {{-- Chart.js Scripts - loaded inline because Filament panels don't support @push --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trafficData = @json($trafficData);
            const deviceData = @json($deviceData);
            const topPagesData = @json($topPagesData);
            const trafficSourcesData = @json($trafficSourcesData);
            const peakHoursData = @json($peakHoursData);
            
            // Check if dark mode
            const isDark = document.documentElement.classList.contains('dark');
            
            // Chart defaults with brighter colors
            Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
            Chart.defaults.font.size = 13;
            Chart.defaults.color = isDark ? 'rgba(255, 255, 255, 0.85)' : 'rgba(0, 0, 0, 0.8)'; // Brighter text
            
            // Common scale options for brighter text
            const scaleOptions = {
                ticks: {
                    color: isDark ? 'rgba(255, 255, 255, 0.85)' : 'rgba(0, 0, 0, 0.8)',
                    font: { size: 12, weight: '500' }
                },
                grid: {
                    color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.08)'
                }
            };
            
            // Common legend options
            const legendOptions = {
                labels: {
                    color: isDark ? 'rgba(255, 255, 255, 0.9)' : 'rgba(0, 0, 0, 0.85)',
                    font: { size: 13, weight: '600' },
                    usePointStyle: true,
                    padding: 20
                }
            };

            // Traffic Overview
            new Chart(document.getElementById('trafficChart'), {
                type: 'line',
                data: trafficData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: legendOptions
                    },
                    scales: {
                        y: { ...scaleOptions, beginAtZero: true },
                        x: { ...scaleOptions, grid: { display: false } }
                    }
                }
            });

            // Device Chart
            new Chart(document.getElementById('deviceChart'), {
                type: 'doughnut',
                data: deviceData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { ...legendOptions, position: 'bottom' }
                    },
                    cutout: '60%'
                }
            });

            // Traffic Sources
            new Chart(document.getElementById('trafficSourcesChart'), {
                type: 'bar',
                data: trafficSourcesData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: legendOptions
                    },
                    scales: {
                        y: { ...scaleOptions, beginAtZero: true },
                        x: scaleOptions
                    }
                }
            });

            // Top Pages
            new Chart(document.getElementById('topPagesChart'), {
                type: 'bar',
                data: topPagesData,
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { 
                        x: { ...scaleOptions, beginAtZero: true },
                        y: scaleOptions
                    }
                }
            });

            // Peak Hours
            new Chart(document.getElementById('peakHoursChart'), {
                type: 'line',
                data: peakHoursData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { ...scaleOptions, beginAtZero: true },
                        x: { ...scaleOptions, grid: { display: false } }
                    }
                }
            });

            // Acquisition Cost Trend (dual y-axis)
            const acquisitionTrendData = @json($acquisitionCostData['performance_trend']);
            new Chart(document.getElementById('acquisitionTrendChart'), {
                type: 'line',
                data: acquisitionTrendData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: legendOptions
                    },
                    scales: {
                        y: {
                            ...scaleOptions,
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: { 
                                display: true, 
                                text: 'CPA (IDR)',
                                color: isDark ? 'rgba(255, 255, 255, 0.9)' : 'rgba(0, 0, 0, 0.85)',
                                font: { size: 13, weight: '600' }
                            },
                            beginAtZero: true,
                        },
                        y1: {
                            ...scaleOptions,
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: { 
                                display: true, 
                                text: 'ROAS (x)',
                                color: isDark ? 'rgba(255, 255, 255, 0.9)' : 'rgba(0, 0, 0, 0.85)',
                                font: { size: 13, weight: '600' }
                            },
                            beginAtZero: true,
                            grid: { drawOnChartArea: false },
                        },
                        x: scaleOptions
                    }
                }
            });

            // Sync heights of Top Pages and Peak Hours charts
            // const topPagesContainer = document.getElementById('topPagesContainer');
            // const peakHoursContainer = document.getElementById('peakHoursContainer');
            
            // if (topPagesContainer && peakHoursContainer) {
            //     const topPagesHeight = topPagesContainer.offsetHeight;
            //     const peakHoursHeight = peakHoursContainer.offsetHeight;
            //     const maxHeight = Math.max(topPagesHeight, peakHoursHeight);
                
            //     topPagesContainer.style.height = maxHeight + 'px';
            //     peakHoursContainer.style.height = maxHeight + 'px';
            // }
        });
    </script>
</x-filament-panels::page>
