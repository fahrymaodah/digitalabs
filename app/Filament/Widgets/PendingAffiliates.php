<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AffiliateResource;
use App\Models\Affiliate;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingAffiliates extends BaseWidget
{
    protected static ?string $heading = 'Pending Affiliate Requests';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Affiliate::query()
                    ->where('status', 'pending')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Applicant')
                    ->searchable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->limit(20),

                TextColumn::make('referral_code')
                    ->badge()
                    ->color('info'),

                TextColumn::make('created_at')
                    ->label('Applied')
                    ->since(),
            ])
            ->recordUrl(fn ($record) => AffiliateResource::getUrl('edit', ['record' => $record]))
            ->paginated(false)
            ->emptyStateHeading('No pending requests')
            ->emptyStateDescription('All affiliate requests have been processed.');
    }
}
