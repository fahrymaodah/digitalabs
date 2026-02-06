<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class AnalyticsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 0;
    
    protected function getStats(): array
    {
        // Check if analytics is configured
        if (!config('analytics.property_id')) {
            return [
                Stat::make('Analytics Setup Required', 'Not Configured')
                    ->description('Please configure Google Analytics credentials')
                    ->descriptionIcon('heroicon-o-information-circle')
                    ->color('warning'),
            ];
        }
        
        try {
            // Only import when actually needed and configured
            if (!class_exists('Spatie\Analytics\Period')) {
                return [
                    Stat::make('Analytics Package', 'Installing...')
                        ->description('Please run composer install')
                        ->descriptionIcon('heroicon-o-information-circle')
                        ->color('warning'),
                ];
            }
            
            $periodClass = 'Spatie\Analytics\Period';
            $analyticsClass = 'Spatie\Analytics\Facades\Analytics';
            
            // Get data for last 7 days
            $period = $periodClass::days(7);
            
            // Total visitors
            $totalVisitors = $analyticsClass::fetchTotalVisitorsAndPageViews($period);
            $visitors = $totalVisitors->sum('screenPageViews') ?? 0;
            
            // Active users (last 28 days)
            $activeUsersPeriod = $periodClass::days(28);
            $activeUsers = $analyticsClass::fetchTotalVisitorsAndPageViews($activeUsersPeriod);
            $activeCount = $activeUsers->sum('activeUsers') ?? 0;
            
            // Top pages
            $topPages = $analyticsClass::fetchMostVisitedPages($period, 5);
            $pageViews = $topPages->sum('screenPageViews') ?? 0;
            
            // Get user analytics for location data
            $userAnalytics = $analyticsClass::get(
                $period,
                metrics: ['activeUsers', 'sessions'],
                dimensions: ['city', 'country']
            );
            
            $topCities = $userAnalytics
                ->sortByDesc('activeUsers')
                ->take(3)
                ->pluck('city')
                ->filter()
                ->join(', ') ?: 'No data yet';

            return [
                Stat::make('Total Visitors (7 days)', number_format($visitors))
                    ->description('Page views in the last week')
                    ->descriptionIcon('heroicon-o-users')
                    ->color('success')
                    ->chart([7, 4, 6, 8, 10, 9, 12]), // Mock trend data
                    
                Stat::make('Active Users (28 days)', number_format($activeCount))
                    ->description('Unique users in the last month')
                    ->descriptionIcon('heroicon-o-user-group')
                    ->color('info'),
                    
                Stat::make('Page Views (7 days)', number_format($pageViews))
                    ->description('Total page views')
                    ->descriptionIcon('heroicon-o-document-text')
                    ->color('warning'),
                    
                Stat::make('Top Cities', $topCities)
                    ->description('Most active locations')
                    ->descriptionIcon('heroicon-o-map-pin')
                    ->color('primary'),
            ];
        } catch (\Exception $e) {
            // Return default stats if Analytics fails
            return [
                Stat::make('Analytics Error', 'Please Check Configuration')
                    ->description($e->getMessage())
                    ->descriptionIcon('heroicon-o-information-circle')
                    ->color('danger'),
            ];
        }
    }
}
