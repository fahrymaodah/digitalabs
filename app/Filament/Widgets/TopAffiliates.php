<?php

namespace App\Filament\Widgets;

use App\Models\Affiliate;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopAffiliates extends BaseWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 1;

    public function getHeading(): ?string
    {
        return '🌟 Top Affiliates';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Affiliate::query()
                    ->where('status', 'approved')
                    ->withCount('commissions')
                    ->withSum(['commissions' => function ($query) {
                        $query->whereHas('order', fn($q) => $q->where('status', 'paid'));
                    }], 'commission_amount')
                    ->orderByDesc('commissions_sum_commission_amount')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->state(fn ($record, $rowLoop) => match($rowLoop->iteration) {
                        1 => '🥇',
                        2 => '🥈',
                        3 => '🥉',
                        default => $rowLoop->iteration,
                    })
                    ->alignCenter(),

                TextColumn::make('user.name')
                    ->label('Affiliate')
                    ->description(fn (Affiliate $record): string => $record->referral_code)
                    ->weight('bold'),

                TextColumn::make('commissions_count')
                    ->label('Sales')
                    ->alignCenter()
                    ->badge()
                    ->color('info'),

                TextColumn::make('commissions_sum_commission_amount')
                    ->label('Total Komisi')
                    ->money('IDR')
                    ->weight('bold')
                    ->color('success'),
            ])
            ->paginated(false)
            ->emptyStateHeading('Belum ada data affiliate')
            ->emptyStateDescription('Data affiliate akan muncul setelah ada transaksi.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
