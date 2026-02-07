<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;
    
    protected static ?string $pollingInterval = '30s';

    public function getHeading(): ?string
    {
        return '🛒 Order Terbaru';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['user', 'items.course'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order')
                    ->description(fn (Order $record): string => $record->created_at->diffForHumans())
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->description(fn (Order $record): string => $record->user?->email ?? '-')
                    ->limit(20),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->weight('bold')
                    ->color(fn (Order $record): string => match($record->status) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-m-clock',
                        'paid' => 'heroicon-m-check-circle',
                        'expired' => 'heroicon-m-x-circle',
                        'cancelled' => 'heroicon-m-no-symbol',
                        default => 'heroicon-m-question-mark-circle',
                    }),
            ])
            ->recordUrl(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record->id]))
            ->paginated(false)
            ->emptyStateHeading('Belum ada order')
            ->emptyStateDescription('Order baru akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-shopping-cart');
    }
}
