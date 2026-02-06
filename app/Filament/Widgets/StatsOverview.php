<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Revenue calculations
        $grossRevenue = Order::where('status', 'paid')->sum('total');
        $monthlyGrossRevenue = Order::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('total');

        // Affiliate commission calculations
        $totalAffiliateCommission = \App\Models\AffiliateCommission::whereHas('order', function($query) {
            $query->where('status', 'paid');
        })->sum('commission_amount');
        
        $monthlyAffiliateCommission = \App\Models\AffiliateCommission::whereHas('order', function($query) {
            $query->where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year);
        })->sum('commission_amount');

        // Net revenue (after affiliate commission)
        $netRevenue = $grossRevenue - $totalAffiliateCommission;
        $monthlyNetRevenue = $monthlyGrossRevenue - $monthlyAffiliateCommission;

        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalCourses = Course::where('status', 'published')->count();

        $pendingOrders = Order::where('status', 'pending')->count();

        return [
            Stat::make('Gross Revenue', 'Rp ' . number_format($grossRevenue, 0, ',', '.'))
                ->description('Monthly: Rp ' . number_format($monthlyGrossRevenue, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info')
                ->chart([7, 4, 6, 8, 10, 12, 15]),

            Stat::make('Net Revenue', 'Rp ' . number_format($netRevenue, 0, ',', '.'))
                ->description('Monthly: Rp ' . number_format($monthlyNetRevenue, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([5, 3, 5, 7, 9, 11, 13]),

            Stat::make('Affiliate Commission', 'Rp ' . number_format($totalAffiliateCommission, 0, ',', '.'))
                ->description('Monthly: Rp ' . number_format($monthlyAffiliateCommission, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning')
                ->chart([2, 1, 1, 1, 1, 1, 2]),

            Stat::make('Total Users', number_format($totalUsers))
                ->description('+' . $newUsersThisMonth . ' this month')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),

            Stat::make('Published Courses', $totalCourses)
                ->description('Active courses')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make('Pending Orders', $pendingOrders)
                ->description('Awaiting payment')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 0 ? 'danger' : 'success'),
        ];
    }
}
