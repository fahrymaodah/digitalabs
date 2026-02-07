<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Widgets\ChartWidget;

class PopularCourses extends ChartWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    public function getHeading(): ?string
    {
        return '🏆 Top 5 Courses Populer';
    }

    public function getDescription(): ?string
    {
        return 'Berdasarkan jumlah enrollments';
    }

    protected function getData(): array
    {
        $courses = Course::withCount('userCourses')
            ->where('status', 'published')
            ->orderByDesc('user_courses_count')
            ->take(5)
            ->get();

        // Gradient-like colors for ranking
        $colors = [
            'rgb(251, 191, 36)',  // Gold - 1st place
            'rgb(156, 163, 175)', // Silver - 2nd place  
            'rgb(180, 83, 9)',    // Bronze - 3rd place
            'rgb(99, 102, 241)',  // Indigo - 4th
            'rgb(34, 197, 94)',   // Green - 5th
        ];

        $bgColors = [
            'rgba(251, 191, 36, 0.8)',
            'rgba(156, 163, 175, 0.8)',
            'rgba(180, 83, 9, 0.8)',
            'rgba(99, 102, 241, 0.8)',
            'rgba(34, 197, 94, 0.8)',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Students',
                    'data' => $courses->pluck('user_courses_count')->toArray(),
                    'backgroundColor' => array_slice($bgColors, 0, $courses->count()),
                    'borderColor' => array_slice($colors, 0, $courses->count()),
                    'borderWidth' => 2,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $courses->pluck('title')->map(function ($title, $index) {
                $medal = match($index) {
                    0 => '🥇 ',
                    1 => '🥈 ',
                    2 => '🥉 ',
                    default => '',
                };
                return $medal . (strlen($title) > 18 ? substr($title, 0, 18) . '...' : $title);
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                        'drawBorder' => false,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Students',
                    ],
                ],
                'y' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
