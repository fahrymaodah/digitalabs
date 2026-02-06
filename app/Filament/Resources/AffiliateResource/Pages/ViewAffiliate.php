<?php

namespace App\Filament\Resources\AffiliateResource\Pages;

use App\Filament\Resources\AffiliateResource;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewAffiliate extends ViewRecord
{
    protected static string $resource = AffiliateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make('Affiliate Information')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Name'),
                        TextEntry::make('user.email')
                            ->label('Email')
                            ->copyable(),
                        TextEntry::make('referral_code')
                            ->badge()
                            ->color('info')
                            ->copyable(),
                        TextEntry::make('commission_rate')
                            ->suffix('%'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'active' => 'success',
                                'suspended' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('created_at')
                            ->dateTime(),
                    ])
                    ->columns(2)
                    ->columnSpan(6),

                Section::make('Earnings Summary')
                    ->schema([
                        TextEntry::make('total_earnings')
                            ->money('IDR')
                            ->weight('bold'),
                        TextEntry::make('pending_earnings')
                            ->money('IDR')
                            ->color('warning'),
                        TextEntry::make('paid_earnings')
                            ->money('IDR')
                            ->color('success'),
                        TextEntry::make('orders_count')
                            ->label('Total Referrals')
                            ->state(fn ($record) => $record->orders()->count()),
                    ])
                    ->columns(2)
                    ->columnSpan(6),
            ]);
    }
}
