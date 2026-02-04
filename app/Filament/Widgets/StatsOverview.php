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
        $totalRevenue = Order::where('status', 'paid')->sum('total');
        $monthlyRevenue = Order::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('total');

        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalCourses = Course::where('status', 'published')->count();

        $pendingOrders = Order::where('status', 'pending')->count();

        return [
            Stat::make('Total Revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Monthly: Rp ' . number_format($monthlyRevenue, 0, ',', '.'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Users', number_format($totalUsers))
                ->description('+' . $newUsersThisMonth . ' this month')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),

            Stat::make('Published Courses', $totalCourses)
                ->description('Active courses')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),

            Stat::make('Pending Orders', $pendingOrders)
                ->description('Awaiting payment')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 0 ? 'danger' : 'success'),
        ];
    }
}
