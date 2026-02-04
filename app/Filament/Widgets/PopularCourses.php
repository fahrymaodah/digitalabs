<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Widgets\ChartWidget;

class PopularCourses extends ChartWidget
{
    protected ?string $heading = 'Popular Courses';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $courses = Course::withCount('userCourses')
            ->orderByDesc('user_courses_count')
            ->take(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Students',
                    'data' => $courses->pluck('user_courses_count')->toArray(),
                    'backgroundColor' => [
                        'rgb(99, 102, 241)',
                        'rgb(236, 72, 153)',
                        'rgb(34, 197, 94)',
                        'rgb(251, 146, 60)',
                        'rgb(14, 165, 233)',
                    ],
                ],
            ],
            'labels' => $courses->pluck('title')->map(fn ($title) => strlen($title) > 20 ? substr($title, 0, 20) . '...' : $title)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
