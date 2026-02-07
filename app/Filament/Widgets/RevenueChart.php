<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'gross';

    protected ?string $maxHeight = '400px';

    public function getHeading(): ?string
    {
        return '📈 Revenue Overview (12 Bulan Terakhir)';
    }

    public function getDescription(): ?string
    {
        return 'Perbandingan revenue tahun ini vs tahun lalu';
    }

    protected function getFilters(): ?array
    {
        return [
            'gross' => 'Gross Revenue',
            'net' => 'Net Revenue (setelah komisi)',
            'orders' => 'Jumlah Transaksi',
        ];
    }

    protected function getData(): array
    {
        $currentYearData = [];
        $lastYearData = [];
        $labels = [];

        // Get last 12 months of data
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $lastYearDate = Carbon::now()->subMonths($i)->subYear();
            $labels[] = $date->translatedFormat('M');

            if ($this->filter === 'orders') {
                $currentYearData[] = Order::where('status', 'paid')
                    ->whereMonth('paid_at', $date->month)
                    ->whereYear('paid_at', $date->year)
                    ->count();

                $lastYearData[] = Order::where('status', 'paid')
                    ->whereMonth('paid_at', $lastYearDate->month)
                    ->whereYear('paid_at', $lastYearDate->year)
                    ->count();
            } else {
                $revenue = Order::where('status', 'paid')
                    ->whereMonth('paid_at', $date->month)
                    ->whereYear('paid_at', $date->year)
                    ->sum('total');

                $lastYearRevenue = Order::where('status', 'paid')
                    ->whereMonth('paid_at', $lastYearDate->month)
                    ->whereYear('paid_at', $lastYearDate->year)
                    ->sum('total');

                if ($this->filter === 'net') {
                    $commission = \App\Models\AffiliateCommission::whereHas('order', function($query) use ($date) {
                        $query->where('status', 'paid')
                            ->whereMonth('paid_at', $date->month)
                            ->whereYear('paid_at', $date->year);
                    })->sum('commission_amount');
                    
                    $lastYearCommission = \App\Models\AffiliateCommission::whereHas('order', function($query) use ($lastYearDate) {
                        $query->where('status', 'paid')
                            ->whereMonth('paid_at', $lastYearDate->month)
                            ->whereYear('paid_at', $lastYearDate->year);
                    })->sum('commission_amount');
                    
                    $revenue -= $commission;
                    $lastYearRevenue -= $lastYearCommission;
                }

                $currentYearData[] = $revenue / 1000000; // Convert to millions
                $lastYearData[] = $lastYearRevenue / 1000000;
            }
        }

        $labelSuffix = $this->filter === 'orders' ? '' : ' (jt)';
        $currentYear = now()->year;
        $lastYear = now()->subYear()->year;

        return [
            'datasets' => [
                [
                    'label' => $currentYear . $labelSuffix,
                    'data' => $currentYearData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                ],
                [
                    'label' => $lastYear . $labelSuffix,
                    'data' => $lastYearData,
                    'backgroundColor' => 'rgba(156, 163, 175, 0.1)',
                    'borderColor' => 'rgb(156, 163, 175)',
                    'borderWidth' => 2,
                    'borderDash' => [5, 5],
                    'fill' => false,
                    'tension' => 0.4,
                    'pointRadius' => 3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                        'drawBorder' => false,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'interaction' => [
                'mode' => 'nearest',
                'axis' => 'x',
                'intersect' => false,
            ],
        ];
    }
}
