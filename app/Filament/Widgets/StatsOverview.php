<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use App\Models\AffiliateCommission;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public function getColumns(): int | array
    {
        return 6;
    }

    protected function getStats(): array
    {
        // Get last 7 days revenue for chart
        $revenueChart = collect(range(6, 0))->map(function ($daysAgo) {
            return Order::where('status', 'paid')
                ->whereDate('paid_at', Carbon::today()->subDays($daysAgo))
                ->sum('total') / 1000000; // Convert to millions for chart readability
        })->toArray();

        // Revenue calculations
        $grossRevenue = Order::where('status', 'paid')->sum('total');
        $monthlyGrossRevenue = Order::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('total');
        
        // Last month comparison
        $lastMonthRevenue = Order::where('status', 'paid')
            ->whereMonth('paid_at', now()->subMonth()->month)
            ->whereYear('paid_at', now()->subMonth()->year)
            ->sum('total');
        
        $revenueGrowth = $lastMonthRevenue > 0 
            ? round((($monthlyGrossRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) 
            : 100;

        // Affiliate commission calculations
        $totalAffiliateCommission = AffiliateCommission::whereHas('order', function($query) {
            $query->where('status', 'paid');
        })->sum('commission_amount');
        
        $monthlyAffiliateCommission = AffiliateCommission::whereHas('order', function($query) {
            $query->where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year);
        })->sum('commission_amount');

        // Net revenue (after affiliate commission)
        $netRevenue = $grossRevenue - $totalAffiliateCommission;
        $monthlyNetRevenue = $monthlyGrossRevenue - $monthlyAffiliateCommission;

        // User stats
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $lastMonthUsers = User::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $userGrowth = $lastMonthUsers > 0 
            ? round((($newUsersThisMonth - $lastMonthUsers) / $lastMonthUsers) * 100, 1) 
            : 100;

        // User chart (last 7 days)
        $userChart = collect(range(6, 0))->map(function ($daysAgo) {
            return User::whereDate('created_at', Carbon::today()->subDays($daysAgo))->count();
        })->toArray();

        $totalCourses = Course::where('status', 'published')->count();
        $totalEnrollments = \App\Models\UserCourse::count();

        // Order stats
        $pendingOrders = Order::where('status', 'pending')->count();
        $todayOrders = Order::whereDate('created_at', today())->count();
        $paidToday = Order::where('status', 'paid')->whereDate('paid_at', today())->count();

        return [
            Stat::make('💰 Total Revenue', 'Rp ' . $this->formatNumber($grossRevenue))
                ->description($this->formatGrowth($revenueGrowth) . ' dari bulan lalu')
                ->descriptionIcon($revenueGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($revenueGrowth >= 0 ? 'success' : 'danger')
                ->chart($revenueChart)
                ->columnSpan(1),

            Stat::make('💵 Net Revenue', 'Rp ' . $this->formatNumber($netRevenue))
                ->description('Bulan ini: Rp ' . $this->formatNumber($monthlyNetRevenue))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart($revenueChart)
                ->columnSpan(1),

            Stat::make('🤝 Komisi Affiliate', 'Rp ' . $this->formatNumber($totalAffiliateCommission))
                ->description('Bulan ini: Rp ' . $this->formatNumber($monthlyAffiliateCommission))
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning')
                ->columnSpan(1),

            Stat::make('👥 Total Users', number_format($totalUsers))
                ->description($this->formatGrowth($userGrowth) . ' (+' . $newUsersThisMonth . ' bulan ini)')
                ->descriptionIcon($userGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($userGrowth >= 0 ? 'info' : 'warning')
                ->chart($userChart)
                ->columnSpan(1),

            Stat::make('📚 Course & Enrollments', $totalCourses . ' courses')
                ->description($totalEnrollments . ' total enrollments')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary')
                ->columnSpan(1),

            Stat::make('🛒 Orders Hari Ini', $todayOrders . ' orders')
                ->description($paidToday . ' paid, ' . $pendingOrders . ' pending')
                ->descriptionIcon($pendingOrders > 5 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-shopping-cart')
                ->color($pendingOrders > 5 ? 'danger' : 'success')
                ->columnSpan(1),
        ];
    }

    private function formatNumber(float $number): string
    {
        if ($number >= 1000000000) {
            return number_format($number / 1000000000, 1, ',', '.') . 'M';
        }
        if ($number >= 1000000) {
            return number_format($number / 1000000, 1, ',', '.') . 'jt';
        }
        if ($number >= 1000) {
            return number_format($number / 1000, 1, ',', '.') . 'rb';
        }
        return number_format($number, 0, ',', '.');
    }

    private function formatGrowth(float $growth): string
    {
        $prefix = $growth >= 0 ? '+' : '';
        return $prefix . $growth . '%';
    }
}
