<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Models\Order;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ViewCourse extends ViewRecord
{
    protected static string $resource = CourseResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Course Details')
                    ->tabs([
                        // TAB 1: PREVIEW
                        Tabs\Tab::make('Preview')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                // Main Content
                                                Grid::make(1)
                                                    ->schema([
                                                        ImageEntry::make('thumbnail')
                                                            ->disk('public')
                                                            ->height(300)
                                                            ->extraImgAttributes(['class' => 'rounded-lg w-full object-cover']),

                                                        TextEntry::make('title')
                                                            ->size('text-3xl')
                                                            ->weight('bold'),

                                                        TextEntry::make('description')
                                                            ->prose(),

                                                        // Course Curriculum - Optimized with eager loading
                                                        Section::make('Course Curriculum')
                                                            ->icon('heroicon-o-academic-cap')
                                                            ->schema([
                                                                RepeatableEntry::make('topics')
                                                                    ->schema([
                                                                        TextEntry::make('title')
                                                                            ->weight('semibold')
                                                                            ->size('lg'),
                                                                        TextEntry::make('description')
                                                                            ->placeholder('No description'),
                                                                        RepeatableEntry::make('lessons')
                                                                            ->schema([
                                                                                Grid::make(4)
                                                                                    ->schema([
                                                                                        TextEntry::make('title')
                                                                                            ->columnSpan(2),
                                                                                        TextEntry::make('formatted_duration')
                                                                                            ->label('Duration')
                                                                                            ->placeholder('-'),
                                                                                        IconEntry::make('is_free')
                                                                                            ->label('Free')
                                                                                            ->boolean(),
                                                                                    ]),
                                                                            ])
                                                                            ->contained(false),
                                                                    ])
                                                                    ->contained(true),
                                                            ]),
                                                    ])
                                                    ->columnSpan(2),

                                                // Sidebar (About Course + Pricing Card)
                                                Grid::make(1)
                                                    ->schema([
                                                        Section::make('About This Course')
                                                            ->icon('heroicon-o-document-text')
                                                            ->schema([
                                                                TextEntry::make('content')
                                                                    ->html()
                                                                    ->prose(),
                                                            ])
                                                            ->collapsible(),

                                                        Section::make('Enroll Now')
                                                            ->schema([
                                                                ImageEntry::make('thumbnail')
                                                                    ->disk('public')
                                                                    ->hiddenLabel()
                                                                    ->extraImgAttributes(['class' => 'rounded-lg w-full']),

                                                                TextEntry::make('sale_price')
                                                                    ->label('Price')
                                                                    ->money('IDR')
                                                                    ->size('text-2xl')
                                                                    ->weight('bold')
                                                                    ->color('success')
                                                                    ->visible(fn ($record) => $record->sale_price > 0),

                                                                TextEntry::make('price')
                                                                    ->label(fn ($record) => $record->sale_price > 0 ? 'Original Price' : 'Price')
                                                                    ->money('IDR')
                                                                    ->size(fn ($record) => $record->sale_price > 0 ? 'text-lg' : 'text-2xl')
                                                                    ->weight('bold')
                                                                    ->extraAttributes(fn ($record) => $record->sale_price > 0 ? ['class' => 'line-through text-gray-400'] : []),

                                                                TextEntry::make('discount')
                                                                    ->label('You Save')
                                                                    ->state(function ($record) {
                                                                        if ($record->sale_price > 0) {
                                                                            $discount = $record->price - $record->sale_price;
                                                                            $percent = round(($discount / $record->price) * 100);
                                                                            return 'Rp ' . number_format($discount, 0, ',', '.') . " ({$percent}%)";
                                                                        }
                                                                        return null;
                                                                    })
                                                                    ->visible(fn ($record) => $record->sale_price > 0)
                                                                    ->color('danger'),

                                                                Section::make('This Course Includes')
                                                                    ->schema([
                                                                        TextEntry::make('total_topics')
                                                                            ->label('Topics')
                                                                            ->icon('heroicon-o-rectangle-stack')
                                                                            ->state(fn ($record) => $record->topics()->count()),
                                                                        TextEntry::make('total_lessons')
                                                                            ->label('Lessons')
                                                                            ->icon('heroicon-o-play-circle')
                                                                            ->state(fn ($record) => $record->topics()->withCount('lessons')->get()->sum('lessons_count')),
                                                                        TextEntry::make('formatted_duration')
                                                                            ->label('Total Duration')
                                                                            ->icon('heroicon-o-clock'),
                                                                        TextEntry::make('access_type')
                                                                            ->label('Access')
                                                                            ->icon('heroicon-o-key')
                                                                            ->formatStateUsing(fn ($state) => $state === 'lifetime' ? 'Lifetime Access' : 'Limited Access'),
                                                                    ])
                                                                    ->compact(),
                                                            ]),

                                                        Section::make('Category')
                                                            ->schema([
                                                                TextEntry::make('category.name')
                                                                    ->badge()
                                                                    ->color('primary'),
                                                                TextEntry::make('status')
                                                                    ->badge()
                                                                    ->color(fn (string $state): string => match ($state) {
                                                                        'draft' => 'warning',
                                                                        'published' => 'success',
                                                                        'archived' => 'danger',
                                                                    }),
                                                            ])
                                                            ->compact(),
                                                    ])
                                                    ->columnSpan(1),
                                            ]),
                                    ]),
                            ]),

                        // TAB 2: ANALYTICS
                        Tabs\Tab::make('Analytics')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        Section::make('Total Sales')
                                            ->schema([
                                                TextEntry::make('total_sales')
                                                    ->label('')
                                                    ->state(fn ($record) => $record->orderItems()->count())
                                                    ->size('text-3xl')
                                                    ->weight('bold')
                                                    ->suffix(' orders'),
                                            ])
                                            ->columnSpan(1),

                                        Section::make('Revenue')
                                            ->schema([
                                                TextEntry::make('revenue')
                                                    ->label('')
                                                    ->state(function ($record) {
                                                        $revenue = Order::whereHas('items', fn ($q) => $q->where('course_id', $record->id))
                                                            ->where('status', 'paid')
                                                            ->sum('total');
                                                        return 'Rp ' . number_format($revenue, 0, ',', '.');
                                                    })
                                                    ->size('text-3xl')
                                                    ->weight('bold')
                                                    ->color('success'),
                                            ])
                                            ->columnSpan(1),

                                        Section::make('Students')
                                            ->schema([
                                                TextEntry::make('students')
                                                    ->label('')
                                                    ->state(fn ($record) => $record->userCourses()->count())
                                                    ->size('text-3xl')
                                                    ->weight('bold')
                                                    ->suffix(' enrolled'),
                                            ])
                                            ->columnSpan(1),

                                        Section::make('Reviews')
                                            ->schema([
                                                TextEntry::make('reviews_count')
                                                    ->label('')
                                                    ->state(function ($record) {
                                                        $count = $record->reviews()->count();
                                                        if ($count === 0) return 'No reviews';
                                                        $avg = $record->reviews()->avg('rating') ?? 0;
                                                        return round($avg, 1) . ' ★ (' . $count . ')';
                                                    })
                                                    ->size('text-3xl')
                                                    ->weight('bold')
                                                    ->color('warning'),
                                            ])
                                            ->columnSpan(1),
                                    ]),

                                // Recent Orders - Optimized with RepeatableEntry
                                Section::make('Recent Orders')
                                    ->icon('heroicon-o-shopping-cart')
                                    ->schema([
                                        RepeatableEntry::make('recent_orders')
                                            ->label('')
                                            ->state(function ($record) {
                                                return Order::whereHas('items', fn ($q) => $q->where('course_id', $record->id))
                                                    ->with('user:id,name,email')
                                                    ->where('status', 'paid')
                                                    ->latest()
                                                    ->take(10)
                                                    ->get(['id', 'user_id', 'total', 'status', 'created_at'])
                                                    ->toArray();
                                            })
                                            ->schema([
                                                Grid::make(10)
                                                    ->schema([
                                                        TextEntry::make('id')
                                                            ->label('')
                                                            ->badge()
                                                            ->color('primary')
                                                            ->formatStateUsing(fn ($state) => '#' . $state)
                                                            ->columnSpan(1),

                                                        TextEntry::make('user.name')
                                                            ->label('')
                                                            ->icon('heroicon-o-user')
                                                            ->columnSpan(3),

                                                        TextEntry::make('total')
                                                            ->label('')
                                                            ->money('IDR')
                                                            ->color('success')
                                                            ->weight('bold')
                                                            ->columnSpan(2),

                                                        TextEntry::make('created_at')
                                                            ->label('')
                                                            ->dateTime('d M Y')
                                                            ->columnSpan(2),

                                                        TextEntry::make('user.email')
                                                            ->label('')
                                                            ->icon('heroicon-o-envelope')
                                                            ->columnSpan(2),
                                                    ]),
                                            ])
                                            ->contained(false),
                                    ])
                                    ->collapsible(),
                            ]),

                        Tabs\Tab::make('Reviews')
                            ->icon('heroicon-o-star')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        TextEntry::make('reviews_summary')
                                            ->label('')
                                            ->state(function ($record) {
                                                $count = $record->reviews()->count();
                                                if ($count === 0) {
                                                    return 'No reviews yet';
                                                }
                                                $avg = $record->reviews()->avg('rating') ?? 0;
                                                return sprintf('Average Rating: %.1f ★ (%d reviews)', $avg, $count);
                                            })
                                            ->size('text-xl')
                                            ->weight('bold')
                                            ->columnSpanFull(),

                                        RepeatableEntry::make('reviews')
                                            ->label('')
                                            ->schema([
                                                Grid::make(12)
                                                    ->schema([
                                                        TextEntry::make('user.name')
                                                            ->label('')
                                                            ->icon('heroicon-o-user')
                                                            ->weight('semibold')
                                                            ->columnSpan(3),

                                                        TextEntry::make('rating')
                                                            ->label('')
                                                            ->formatStateUsing(fn ($state) => str_repeat('★', $state) . str_repeat('☆', 5 - $state))
                                                            ->color('warning')
                                                            ->columnSpan(2),

                                                        TextEntry::make('title')
                                                            ->label('')
                                                            ->weight('medium')
                                                            ->columnSpan(4),

                                                        TextEntry::make('status')
                                                            ->label('')
                                                            ->badge()
                                                            ->color(fn (string $state): string => match ($state) {
                                                                'pending' => 'warning',
                                                                'approved' => 'success',
                                                                'rejected' => 'danger',
                                                                default => 'gray',
                                                            })
                                                            ->columnSpan(2),

                                                        TextEntry::make('created_at')
                                                            ->label('')
                                                            ->dateTime('d M Y')
                                                            ->color('gray')
                                                            ->columnSpan(1),

                                                        TextEntry::make('content')
                                                            ->label('')
                                                            ->prose()
                                                            ->columnSpanFull(),
                                                    ]),
                                            ])
                                            ->contained(true),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
