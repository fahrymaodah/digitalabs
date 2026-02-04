<?php

namespace App\Filament\Resources\AffiliatePayoutResource\Pages;

use App\Filament\Resources\AffiliatePayoutResource;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewAffiliatePayout extends ViewRecord
{
    protected static string $resource = AffiliatePayoutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        Section::make('Payout Information')
                            ->schema([
                                TextEntry::make('affiliate.user.name')
                                    ->label('Affiliate'),
                                TextEntry::make('affiliate.referral_code')
                                    ->label('Referral Code')
                                    ->badge()
                                    ->color('info'),
                                TextEntry::make('amount')
                                    ->money('IDR'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'completed' => 'success',
                                        'failed' => 'danger',
                                        default => 'gray',
                                    }),
                                TextEntry::make('created_at')
                                    ->label('Requested At')
                                    ->dateTime(),
                                TextEntry::make('notes')
                                    ->placeholder('No notes'),
                            ])
                            ->columns(2),

                        Section::make('Bank Details')
                            ->schema([
                                TextEntry::make('bank_name')
                                    ->placeholder('Not provided'),
                                TextEntry::make('bank_account_number')
                                    ->placeholder('Not provided'),
                                TextEntry::make('bank_account_name')
                                    ->placeholder('Not provided'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}
