<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Spatie\Analytics\Period;
use Spatie\Analytics\Facades\Analytics as AnalyticsFacade;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class Analytics extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    
    protected static string|\UnitEnum|null $navigationGroup = '';
    
    protected static ?int $navigationSort = 2;
    
    protected string $view = 'filament.pages.analytics';

    public array $trafficData = [];
    public array $deviceData = [];
    public array $topPagesData = [];
    public array $geographicData = [];
    public array $trafficSourcesData = [];
    public array $peakHoursData = [];
    public array $browserData = [];
    
    // New properties
    public array $conversionData = [];
    public array $landingPagesData = [];
    public array $exitPagesData = [];
    public array $demographicsData = [];
    public array $acquisitionCostData = [];
    public array $devicePercentages = [];

    public function mount(): void
    {
        $this->loadAnalyticsData();
    }

    protected function loadAnalyticsData(): void
    {
        // Use real GA4 data with fallback to mock if fails
        try {
            // Original 7 data
            $this->trafficData = $this->getTrafficOverview();
            $this->deviceData = $this->getDeviceBreakdown();
            $this->topPagesData = $this->getTopPages();
            $this->geographicData = $this->getGeographicData();
            $this->trafficSourcesData = $this->getTrafficSources();
            $this->peakHoursData = $this->getPeakHours();
            $this->browserData = $this->getBrowserStats();
            
            // New 6 data (for conversion tracking & ad targeting)
            $this->conversionData = $this->getConversionTracking();
            $this->landingPagesData = $this->getLandingPagesPerformance();
            $this->exitPagesData = $this->getExitPagesAnalysis();
            $this->demographicsData = $this->getAudienceDemographics();
            $this->acquisitionCostData = $this->getAcquisitionCostAnalysis();
            $this->devicePercentages = $this->calculateDevicePercentages();
        } catch (\Exception $e) {
            // Fallback to mock data if GA4 fails or no data yet
            \Log::warning('GA4 Analytics failed, using mock data: ' . $e->getMessage());
            
            $this->trafficData = $this->getMockTrafficData();
            $this->deviceData = $this->getMockDeviceData();
            $this->topPagesData = $this->getMockTopPages();
            $this->geographicData = $this->getMockGeographicData();
            $this->trafficSourcesData = $this->getMockTrafficSources();
            $this->peakHoursData = $this->getMockPeakHours();
            $this->browserData = $this->getMockBrowserData();
            $this->conversionData = $this->getMockConversionData();
            $this->landingPagesData = $this->getMockLandingPages();
            $this->exitPagesData = $this->getMockExitPages();
            $this->demographicsData = $this->getMockDemographics();
            $this->acquisitionCostData = $this->getMockAcquisitionCost();
            $this->devicePercentages = $this->calculateDevicePercentages();
        }
    }

    protected function getTrafficOverview(): array
    {
        $period = Period::days(30);
        $data = AnalyticsFacade::get($period, 
            metrics: ['activeUsers', 'sessions', 'screenPageViews'],
            dimensions: ['date']
        );

        return [
            'labels' => $data->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M d'))->toArray(),
            'datasets' => [
                [
                    'label' => 'Active Users',
                    'data' => $data->pluck('activeUsers')->toArray(),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Sessions',
                    'data' => $data->pluck('sessions')->toArray(),
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Page Views',
                    'data' => $data->pluck('screenPageViews')->toArray(),
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'tension' => 0.4,
                ],
            ],
        ];
    }

    protected function getDeviceBreakdown(): array
    {
        $period = Period::days(30);
        $data = AnalyticsFacade::get($period,
            metrics: ['activeUsers', 'sessions'],
            dimensions: ['deviceCategory']
        );

        return [
            'labels' => $data->pluck('deviceCategory')->toArray(),
            'datasets' => [
                [
                    'data' => $data->pluck('activeUsers')->toArray(),
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                    ],
                ],
            ],
        ];
    }

    protected function getTopPages(): array
    {
        $period = Period::days(30);
        $data = AnalyticsFacade::fetchMostVisitedPages($period, 10);

        return [
            'labels' => $data->pluck('pageTitle')->map(fn($t) => strlen($t) > 30 ? substr($t, 0, 30) . '...' : $t)->toArray(),
            'datasets' => [
                [
                    'label' => 'Page Views',
                    'data' => $data->pluck('screenPageViews')->toArray(),
                    'backgroundColor' => 'rgba(99, 102, 241, 0.8)',
                ],
            ],
        ];
    }

    protected function getGeographicData(): array
    {
        $period = Period::days(30);
        $data = AnalyticsFacade::get($period,
            metrics: ['activeUsers', 'sessions'],
            dimensions: ['city', 'country']
        );

        return $data->sortByDesc('activeUsers')
            ->take(20)
            ->map(fn($item) => [
                'city' => $item['city'] ?? 'Unknown',
                'country' => $item['country'] ?? 'Unknown',
                'users' => $item['activeUsers'],
                'sessions' => $item['sessions'],
            ])
            ->toArray();
    }

    protected function getTrafficSources(): array
    {
        $period = Period::days(30);
        $data = AnalyticsFacade::get($period,
            metrics: ['sessions', 'newUsers'],
            dimensions: ['sessionSource', 'sessionMedium']
        );

        $grouped = $data->groupBy('sessionSource')->map(fn($items) => [
            'sessions' => $items->sum('sessions'),
            'newUsers' => $items->sum('newUsers'),
        ])->sortByDesc('sessions')->take(10);

        return [
            'labels' => $grouped->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Sessions',
                    'data' => $grouped->pluck('sessions')->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                ],
                [
                    'label' => 'New Users',
                    'data' => $grouped->pluck('newUsers')->toArray(),
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                ],
            ],
        ];
    }

    protected function getPeakHours(): array
    {
        $period = Period::days(7);
        $data = AnalyticsFacade::get($period,
            metrics: ['activeUsers', 'sessions'],
            dimensions: ['hour']
        );

        $hourlyData = collect(range(0, 23))->map(function($hour) use ($data) {
            $found = $data->firstWhere('hour', str_pad($hour, 2, '0', STR_PAD_LEFT));
            return $found ? $found['activeUsers'] : 0;
        });

        return [
            'labels' => collect(range(0, 23))->map(fn($h) => $h . ':00')->toArray(),
            'datasets' => [
                [
                    'label' => 'Active Users',
                    'data' => $hourlyData->toArray(),
                    'borderColor' => 'rgb(139, 92, 246)',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
        ];
    }

    protected function getBrowserStats(): array
    {
        $period = Period::days(30);
        $data = AnalyticsFacade::get($period,
            metrics: ['activeUsers', 'bounceRate'],
            dimensions: ['browser', 'operatingSystem']
        );

        return $data->sortByDesc('activeUsers')
            ->take(15)
            ->map(fn($item) => [
                'browser' => $item['browser'] ?? 'Unknown',
                'os' => $item['operatingSystem'] ?? 'Unknown',
                'users' => $item['activeUsers'],
                'bounceRate' => number_format($item['bounceRate'] * 100, 1) . '%',
            ])
            ->toArray();
    }

    // === REAL GA4 METHODS FOR NEW FEATURES ===
    // These methods use GA4 API to fetch conversion, landing pages, exit pages, demographics, and acquisition cost data
    
    protected function getConversionTracking(): array
    {
        $period = Period::days(30);
        
        // Get conversion events
        $conversions = AnalyticsFacade::get($period,
            metrics: ['conversions', 'totalRevenue', 'eventCount'],
            dimensions: ['eventName']
        );
        
        // Get funnel data
        $visitors = AnalyticsFacade::get($period, metrics: ['totalUsers']);
        $courseViews = AnalyticsFacade::get($period, 
            metrics: ['eventCount'],
            dimensions: ['eventName']
        )->where('eventName', 'page_view')->first();
        
        $purchases = $conversions->where('eventName', 'purchase')->first();
        $enrollments = $conversions->where('eventName', 'course_enrollment')->first();
        $registrations = $conversions->where('eventName', 'sign_up')->first();
        
        $totalConversions = $conversions->sum('conversions');
        $totalRevenue = $conversions->sum('totalRevenue');
        $totalVisitors = $visitors->first()['totalUsers'] ?? 1;
        
        return [
            'total_conversions' => $totalConversions,
            'conversion_rate' => round(($totalConversions / $totalVisitors) * 100, 1),
            'total_value' => $totalRevenue,
            'avg_order_value' => $totalConversions > 0 ? $totalRevenue / $totalConversions : 0,
            'by_type' => $conversions->map(fn($conv) => [
                'type' => $conv['eventName'],
                'count' => $conv['conversions'],
                'value' => $conv['totalRevenue'] ?? 0,
                'rate' => round(($conv['conversions'] / $totalVisitors) * 100, 1) . '%',
            ])->toArray(),
            'funnel' => [
                ['stage' => 'Visitors', 'count' => $totalVisitors, 'percentage' => 100],
                ['stage' => 'Course View', 'count' => $courseViews['eventCount'] ?? 0, 'percentage' => round((($courseViews['eventCount'] ?? 0) / $totalVisitors) * 100, 1)],
                ['stage' => 'Add to Cart', 'count' => ($conversions->where('eventName', 'add_to_cart')->first()['conversions'] ?? 0), 'percentage' => 0],
                ['stage' => 'Checkout Started', 'count' => ($conversions->where('eventName', 'begin_checkout')->first()['conversions'] ?? 0), 'percentage' => 0],
                ['stage' => 'Purchase Completed', 'count' => ($purchases['conversions'] ?? 0), 'percentage' => round((($purchases['conversions'] ?? 0) / $totalVisitors) * 100, 1)],
            ],
        ];
    }

    protected function getLandingPagesPerformance(): array
    {
        $period = Period::days(30);
        
        $data = AnalyticsFacade::get($period,
            metrics: ['sessions', 'bounceRate', 'averageSessionDuration', 'conversions'],
            dimensions: ['landingPage']
        );
        
        return $data->sortByDesc('sessions')
            ->take(10)
            ->map(fn($item) => [
                'page' => $item['landingPage'],
                'sessions' => $item['sessions'],
                'bounceRate' => round($item['bounceRate'] * 100, 1),
                'avgDuration' => gmdate('i:s', $item['averageSessionDuration']),
                'conversions' => $item['conversions'] ?? 0,
                'conversionRate' => $item['sessions'] > 0 ? round((($item['conversions'] ?? 0) / $item['sessions']) * 100, 2) : 0,
            ])
            ->toArray();
    }

    protected function getExitPagesAnalysis(): array
    {
        $period = Period::days(30);
        
        $data = AnalyticsFacade::get($period,
            metrics: ['exits', 'screenPageViews', 'averageSessionDuration'],
            dimensions: ['pagePath']
        );
        
        return $data->sortByDesc('exits')
            ->take(10)
            ->map(fn($item) => [
                'page' => $item['pagePath'],
                'exits' => $item['exits'],
                'exitRate' => $item['screenPageViews'] > 0 ? round(($item['exits'] / $item['screenPageViews']) * 100, 1) : 0,
                'pageViews' => $item['screenPageViews'],
                'avgTimeBeforeExit' => gmdate('i:s', $item['averageSessionDuration']),
            ])
            ->toArray();
    }

    protected function getAudienceDemographics(): array
    {
        $period = Period::days(30);
        
        // Age data
        $ageData = AnalyticsFacade::get($period,
            metrics: ['activeUsers', 'conversions'],
            dimensions: ['userAgeBracket']
        );
        
        // Gender data
        $genderData = AnalyticsFacade::get($period,
            metrics: ['activeUsers', 'conversions'],
            dimensions: ['userGender']
        );
        
        // Interests (if available - requires Google Ads linking)
        $interestsData = AnalyticsFacade::get($period,
            metrics: ['activeUsers'],
            dimensions: ['interests']
        )->sortByDesc('activeUsers')->take(6);
        
        $totalUsers = $ageData->sum('activeUsers');
        
        return [
            'age' => $ageData->map(fn($item) => [
                'range' => $item['userAgeBracket'],
                'users' => $item['activeUsers'],
                'percentage' => $totalUsers > 0 ? round(($item['activeUsers'] / $totalUsers) * 100, 1) : 0,
                'conversions' => $item['conversions'] ?? 0,
            ])->toArray(),
            'gender' => $genderData->map(fn($item) => [
                'gender' => $item['userGender'],
                'users' => $item['activeUsers'],
                'percentage' => $totalUsers > 0 ? round(($item['activeUsers'] / $totalUsers) * 100, 1) : 0,
                'conversions' => $item['conversions'] ?? 0,
            ])->toArray(),
            'interests' => $interestsData->map(fn($item) => [
                'category' => $item['interests'] ?? 'Unknown',
                'affinity' => $item['activeUsers'] > 100 ? 'High' : ($item['activeUsers'] > 50 ? 'Medium' : 'Low'),
                'users' => $item['activeUsers'],
            ])->toArray(),
        ];
    }

    protected function getAcquisitionCostAnalysis(): array
    {
        $period = Period::days(30);
        
        // This requires Google Ads integration for cost data
        // For now, we'll use session source data and estimate costs
        $sourceData = AnalyticsFacade::get($period,
            metrics: ['sessions', 'conversions', 'newUsers'],
            dimensions: ['sessionSource', 'sessionMedium']
        );
        
        // Filter paid channels
        $paidChannels = $sourceData->filter(function($item) {
            return in_array($item['sessionMedium'], ['cpc', 'cpm', 'paid']);
        });
        
        $totalConversions = $paidChannels->sum('conversions');
        
        // Group by source
        $byChannel = $paidChannels->groupBy('sessionSource')->map(fn($items, $source) => [
            'channel' => $source,
            'clicks' => $items->sum('sessions'),
            'conversions' => $items->sum('conversions'),
            'cpc' => 0, // Requires Google Ads API
            'cpa' => 0, // Requires cost data
            'spent' => 0, // Requires Google Ads API
            'roas' => 0, // Requires revenue tracking
        ])->values();
        
        return [
            'summary' => [
                'total_spent' => 0, // Requires Google Ads API
                'total_conversions' => $totalConversions,
                'avg_cpa' => 0,
                'roas' => 0,
            ],
            'by_channel' => $byChannel->toArray(),
            'performance_trend' => [
                'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                'datasets' => [
                    ['label' => 'Cost Per Acquisition', 'data' => [0, 0, 0, 0], 'borderColor' => 'rgb(239, 68, 68)', 'backgroundColor' => 'rgba(239, 68, 68, 0.1)'],
                    ['label' => 'ROAS', 'data' => [0, 0, 0, 0], 'borderColor' => 'rgb(34, 197, 94)', 'backgroundColor' => 'rgba(34, 197, 94, 0.1)', 'yAxisID' => 'y1'],
                ],
            ],
        ];
    }

    // Mock data methods for when GA4 is not ready
    protected function getMockTrafficData(): array
    {
        $labels = collect(range(29, 0))->map(fn($d) => Carbon::now()->subDays($d)->format('M d'));
        return [
            'labels' => $labels->toArray(),
            'datasets' => [
                [
                    'label' => 'Active Users',
                    'data' => collect(range(1, 30))->map(fn() => rand(100, 500))->toArray(),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Sessions',
                    'data' => collect(range(1, 30))->map(fn() => rand(150, 600))->toArray(),
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Page Views',
                    'data' => collect(range(1, 30))->map(fn() => rand(300, 1200))->toArray(),
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'tension' => 0.4,
                ],
            ],
        ];
    }

    protected function getMockDeviceData(): array
    {
        return [
            'labels' => ['Desktop', 'Mobile', 'Tablet'],
            'datasets' => [
                [
                    'data' => [345, 289, 56],
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                    ],
                ],
            ],
        ];
    }

    protected function getMockTopPages(): array
    {
        return [
            'labels' => ['Home', 'Courses', 'Course: Laravel', 'Login', 'About', 'Course: React', 'Pricing', 'Blog', 'Contact', 'FAQ'],
            'datasets' => [
                [
                    'label' => 'Page Views',
                    'data' => [1250, 890, 750, 620, 580, 490, 430, 380, 320, 290],
                    'backgroundColor' => 'rgba(99, 102, 241, 0.8)',
                ],
            ],
        ];
    }

    protected function getMockGeographicData(): array
    {
        return [
            ['city' => 'Jakarta', 'country' => 'Indonesia', 'users' => 245, 'sessions' => 389],
            ['city' => 'Surabaya', 'country' => 'Indonesia', 'users' => 178, 'sessions' => 267],
            ['city' => 'Bandung', 'country' => 'Indonesia', 'users' => 156, 'sessions' => 234],
            ['city' => 'Medan', 'country' => 'Indonesia', 'users' => 123, 'sessions' => 189],
            ['city' => 'Semarang', 'country' => 'Indonesia', 'users' => 98, 'sessions' => 145],
            ['city' => 'Yogyakarta', 'country' => 'Indonesia', 'users' => 87, 'sessions' => 132],
            ['city' => 'Makassar', 'country' => 'Indonesia', 'users' => 76, 'sessions' => 112],
            ['city' => 'Palembang', 'country' => 'Indonesia', 'users' => 65, 'sessions' => 98],
            ['city' => 'Tangerang', 'country' => 'Indonesia', 'users' => 54, 'sessions' => 87],
            ['city' => 'Bekasi', 'country' => 'Indonesia', 'users' => 43, 'sessions' => 76],
        ];
    }

    protected function getMockTrafficSources(): array
    {
        return [
            'labels' => ['Google', 'Direct', 'Facebook', 'Instagram', 'YouTube', 'Twitter', 'LinkedIn', 'Email', 'Referral', 'Other'],
            'datasets' => [
                [
                    'label' => 'Sessions',
                    'data' => [450, 320, 280, 210, 180, 150, 120, 100, 90, 70],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                ],
                [
                    'label' => 'New Users',
                    'data' => [380, 250, 230, 180, 150, 120, 95, 80, 70, 55],
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                ],
            ],
        ];
    }

    protected function getMockPeakHours(): array
    {
        $mockData = [12, 8, 5, 3, 2, 4, 15, 35, 65, 85, 95, 110, 120, 115, 105, 98, 110, 125, 135, 120, 95, 65, 45, 25];
        return [
            'labels' => collect(range(0, 23))->map(fn($h) => $h . ':00')->toArray(),
            'datasets' => [
                [
                    'label' => 'Active Users',
                    'data' => $mockData,
                    'borderColor' => 'rgb(139, 92, 246)',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
        ];
    }

    protected function getMockBrowserData(): array
    {
        return [
            ['browser' => 'Chrome', 'os' => 'Windows', 'users' => 345, 'bounceRate' => '45.2%'],
            ['browser' => 'Chrome', 'os' => 'Android', 'users' => 234, 'bounceRate' => '48.7%'],
            ['browser' => 'Safari', 'os' => 'iOS', 'users' => 189, 'bounceRate' => '42.3%'],
            ['browser' => 'Safari', 'os' => 'Macintosh', 'users' => 156, 'bounceRate' => '41.8%'],
            ['browser' => 'Firefox', 'os' => 'Windows', 'users' => 98, 'bounceRate' => '50.1%'],
            ['browser' => 'Edge', 'os' => 'Windows', 'users' => 76, 'bounceRate' => '47.5%'],
            ['browser' => 'Samsung Internet', 'os' => 'Android', 'users' => 54, 'bounceRate' => '52.3%'],
            ['browser' => 'Opera', 'os' => 'Windows', 'users' => 43, 'bounceRate' => '49.8%'],
            ['browser' => 'Chrome', 'os' => 'Linux', 'users' => 32, 'bounceRate' => '44.6%'],
            ['browser' => 'Firefox', 'os' => 'Linux', 'users' => 21, 'bounceRate' => '46.9%'],
        ];
    }

    // === NEW MOCK METHODS ===

    protected function getMockConversionData(): array
    {
        return [
            'total_conversions' => 156,
            'conversion_rate' => 3.2,
            'total_value' => 45600000, // IDR
            'avg_order_value' => 292308, // IDR
            'by_type' => [
                ['type' => 'Course Purchase', 'count' => 89, 'value' => 32000000, 'rate' => '2.1%'],
                ['type' => 'Course Enrollment (Free)', 'count' => 234, 'value' => 0, 'rate' => '5.8%'],
                ['type' => 'Account Registration', 'count' => 445, 'value' => 0, 'rate' => '10.2%'],
                ['type' => 'Newsletter Signup', 'count' => 178, 'value' => 0, 'rate' => '4.3%'],
            ],
            'funnel' => [
                ['stage' => 'Visitors', 'count' => 4250, 'percentage' => 100],
                ['stage' => 'Course View', 'count' => 1825, 'percentage' => 42.9],
                ['stage' => 'Add to Cart', 'count' => 456, 'percentage' => 10.7],
                ['stage' => 'Checkout Started', 'count' => 234, 'percentage' => 5.5],
                ['stage' => 'Purchase Completed', 'count' => 89, 'percentage' => 2.1],
            ],
        ];
    }

    protected function getMockLandingPages(): array
    {
        return [
            ['page' => '/courses/web-development', 'sessions' => 856, 'bounceRate' => 35.2, 'avgDuration' => '04:32', 'conversions' => 28, 'conversionRate' => 3.27],
            ['page' => '/', 'sessions' => 1234, 'bounceRate' => 52.8, 'avgDuration' => '02:15', 'conversions' => 34, 'conversionRate' => 2.75],
            ['page' => '/courses/digital-marketing', 'sessions' => 654, 'bounceRate' => 38.4, 'avgDuration' => '03:58', 'conversions' => 22, 'conversionRate' => 3.36],
            ['page' => '/courses/graphic-design', 'sessions' => 543, 'bounceRate' => 41.2, 'avgDuration' => '03:24', 'conversions' => 15, 'conversionRate' => 2.76],
            ['page' => '/promo/ramadan-sale', 'sessions' => 432, 'bounceRate' => 28.5, 'avgDuration' => '05:12', 'conversions' => 19, 'conversionRate' => 4.40],
            ['page' => '/courses/python-programming', 'sessions' => 387, 'bounceRate' => 44.7, 'avgDuration' => '03:45', 'conversions' => 11, 'conversionRate' => 2.84],
            ['page' => '/blog/tips-belajar-coding', 'sessions' => 298, 'bounceRate' => 67.8, 'avgDuration' => '01:45', 'conversions' => 3, 'conversionRate' => 1.01],
            ['page' => '/courses/ui-ux-design', 'sessions' => 276, 'bounceRate' => 39.9, 'avgDuration' => '03:56', 'conversions' => 9, 'conversionRate' => 3.26],
            ['page' => '/affiliate-program', 'sessions' => 198, 'bounceRate' => 58.6, 'avgDuration' => '02:34', 'conversions' => 12, 'conversionRate' => 6.06],
            ['page' => '/courses/video-editing', 'sessions' => 187, 'bounceRate' => 42.8, 'avgDuration' => '03:18', 'conversions' => 5, 'conversionRate' => 2.67],
        ];
    }

    protected function getMockExitPages(): array
    {
        return [
            ['page' => '/checkout', 'exits' => 189, 'exitRate' => 67.4, 'pageViews' => 280, 'avgTimeBeforeExit' => '02:45'],
            ['page' => '/courses/web-development/syllabus', 'exits' => 156, 'exitRate' => 45.3, 'pageViews' => 344, 'avgTimeBeforeExit' => '04:12'],
            ['page' => '/cart', 'exits' => 145, 'exitRate' => 58.2, 'pageViews' => 249, 'avgTimeBeforeExit' => '01:58'],
            ['page' => '/', 'exits' => 134, 'exitRate' => 52.8, 'pageViews' => 254, 'avgTimeBeforeExit' => '01:34'],
            ['page' => '/courses/digital-marketing/pricing', 'exits' => 98, 'exitRate' => 54.7, 'pageViews' => 179, 'avgTimeBeforeExit' => '03:22'],
            ['page' => '/login', 'exits' => 87, 'exitRate' => 48.9, 'pageViews' => 178, 'avgTimeBeforeExit' => '01:12'],
            ['page' => '/payment/failed', 'exits' => 76, 'exitRate' => 89.4, 'pageViews' => 85, 'avgTimeBeforeExit' => '00:45'],
            ['page' => '/courses', 'exits' => 65, 'exitRate' => 32.5, 'pageViews' => 200, 'avgTimeBeforeExit' => '05:34'],
            ['page' => '/register', 'exits' => 54, 'exitRate' => 41.2, 'pageViews' => 131, 'avgTimeBeforeExit' => '02:15'],
            ['page' => '/faq', 'exits' => 43, 'exitRate' => 38.7, 'pageViews' => 111, 'avgTimeBeforeExit' => '03:45'],
        ];
    }

    protected function getMockDemographics(): array
    {
        return [
            'age' => [
                ['range' => '18-24', 'users' => 456, 'percentage' => 32.4, 'conversions' => 45],
                ['range' => '25-34', 'users' => 623, 'percentage' => 44.2, 'conversions' => 78],
                ['range' => '35-44', 'users' => 234, 'percentage' => 16.6, 'conversions' => 25],
                ['range' => '45-54', 'users' => 76, 'percentage' => 5.4, 'conversions' => 6],
                ['range' => '55+', 'users' => 21, 'percentage' => 1.4, 'conversions' => 1],
            ],
            'gender' => [
                ['gender' => 'Male', 'users' => 789, 'percentage' => 56.0, 'conversions' => 89],
                ['gender' => 'Female', 'users' => 621, 'percentage' => 44.0, 'conversions' => 66],
            ],
            'interests' => [
                ['category' => 'Technology', 'affinity' => 'High', 'users' => 543],
                ['category' => 'Education', 'affinity' => 'High', 'users' => 489],
                ['category' => 'Business', 'affinity' => 'Medium', 'users' => 356],
                ['category' => 'Design & Arts', 'affinity' => 'Medium', 'users' => 298],
                ['category' => 'Marketing', 'affinity' => 'Medium', 'users' => 234],
                ['category' => 'Career Development', 'affinity' => 'Low', 'users' => 187],
            ],
        ];
    }

    protected function getMockAcquisitionCost(): array
    {
        return [
            'summary' => [
                'total_spent' => 15000000, // IDR
                'total_conversions' => 89,
                'avg_cpa' => 168539, // Cost Per Acquisition
                'roas' => 2.8, // Return on Ad Spend
            ],
            'by_channel' => [
                ['channel' => 'Google Ads', 'spent' => 6500000, 'clicks' => 3450, 'cpc' => 1884, 'conversions' => 42, 'cpa' => 154762, 'roas' => 3.2],
                ['channel' => 'Facebook Ads', 'spent' => 5000000, 'clicks' => 4200, 'cpc' => 1190, 'conversions' => 28, 'cpa' => 178571, 'roas' => 2.6],
                ['channel' => 'Instagram Ads', 'spent' => 2500000, 'clicks' => 2100, 'cpc' => 1190, 'conversions' => 12, 'cpa' => 208333, 'roas' => 2.4],
                ['channel' => 'TikTok Ads', 'spent' => 1000000, 'clicks' => 1800, 'cpc' => 556, 'conversions' => 7, 'cpa' => 142857, 'roas' => 3.5],
            ],
            'performance_trend' => [
                'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                'datasets' => [
                    [
                        'label' => 'Cost Per Acquisition',
                        'data' => [185000, 172000, 165000, 158000],
                        'borderColor' => 'rgb(239, 68, 68)',
                        'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    ],
                    [
                        'label' => 'ROAS',
                        'data' => [2.3, 2.5, 2.7, 2.9],
                        'borderColor' => 'rgb(34, 197, 94)',
                        'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                        'yAxisID' => 'y1',
                    ],
                ],
            ],
        ];
    }

    protected function calculateDevicePercentages(): array
    {
        // Calculate from existing deviceData
        $total = array_sum($this->deviceData['datasets'][0]['data'] ?? [1, 1, 1]);
        $data = $this->deviceData['datasets'][0]['data'] ?? [0, 0, 0];
        $labels = $this->deviceData['labels'] ?? ['Desktop', 'Mobile', 'Tablet'];
        
        return array_map(function($value, $label) use ($total) {
            return [
                'device' => $label,
                'count' => $value,
                'percentage' => $total > 0 ? round(($value / $total) * 100, 1) : 0,
            ];
        }, $data, $labels);
    }
}
