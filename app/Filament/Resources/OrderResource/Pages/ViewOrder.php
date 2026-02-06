<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

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
                Section::make('Order Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('order_number')
                                    ->copyable(),
                                TextEntry::make('user.name')
                                    ->label('Customer'),
                                TextEntry::make('user.email')
                                    ->label('Email')
                                    ->copyable(),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'paid' => 'success',
                                        'expired' => 'danger',
                                        'cancelled' => 'gray',
                                        default => 'gray',
                                    }),
                                TextEntry::make('created_at')
                                    ->label('Order Date')
                                    ->dateTime(),
                                TextEntry::make('paid_at')
                                    ->dateTime()
                                    ->placeholder('Not paid'),
                            ]),
                    ])
                    ->columnSpan(6),

                Section::make('Payment Details')
                    ->schema([
                        TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->badge()
                            ->color(fn (?string $state): string => match ($state) {
                                'duitku' => 'info',
                                'bank_transfer' => 'warning',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'duitku' => 'Duitku (Online)',
                                'bank_transfer' => 'Bank Transfer (Manual)',
                                default => $state ?? 'Not selected',
                            }),
                        TextEntry::make('duitku_reference')
                            ->label('Payment Reference')
                            ->placeholder('N/A')
                            ->copyable(),
                    ])
                    ->columnSpan(3),

                Section::make('Affiliate')
                    ->schema([
                        TextEntry::make('affiliate.user.name')
                            ->label('Affiliate')
                            ->placeholder('No affiliate'),
                        TextEntry::make('affiliate.referral_code')
                            ->label('Referral Code')
                            ->placeholder('N/A'),
                        TextEntry::make('commission.0.commission_amount')
                            ->label('Commission Amount')
                            ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state ?? 0, 0, ',', '.'))
                            ->placeholder('N/A'),
                    ])
                    ->columnSpan(3),

                Section::make('Price Breakdown')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state, 0, ',', '.'))
                                    ->helperText('Harga produk'),

                                TextEntry::make('discount')
                                    ->label('Discount')
                                    ->formatStateUsing(fn($state, $record) => 
                                        ($state == 0 && !$record->coupon) 
                                            ? '-' 
                                            : '- ' . ('IDR ' . number_format($state, 0, ',', '.') . '<br>' . 
                                            ($record->coupon ? '<span class="rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 inset-ring inset-ring-green-500/20">' . strtoupper($record->coupon->code) . '</span>' : '-'))
                                    )
                                    ->html()
                                    ->color('danger'),

                                TextEntry::make('payment_fee')
                                    ->label('Payment Fee')
                                    ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state, 0, ',', '.'))
                                    ->color('warning')
                                    ->prefix('+ ')
                                    ->helperText('Unique code / gateway fee'),

                                TextEntry::make('total')
                                    ->label('Total Paid (Customer)')
                                    ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state, 0, ',', '.'))
                                    ->weight('bold')
                                    ->color('info')
                                    ->helperText('Yang dibayar customer'),

                                
                            ]),
                        
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('net_revenue')
                                    ->label('Net Revenue (Business)')
                                    ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state, 0, ',', '.'))
                                    ->weight('bold')
                                    ->size('lg')
                                    ->color('success')
                                    ->state(fn ($record) => $record->total - ($record->commission->first()?->commission_amount ?? 0))
                                    ->helperText('Pendapatan bersih setelah komisi affiliate'),

                                TextEntry::make('commission.0.commission_amount')
                                    ->label('Affiliate Commission')
                                    ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state ?? 0, 0, ',', '.'))
                                    ->weight('bold')
                                    ->size('lg')
                                    ->color('warning')
                                    ->prefix('- ')
                                    ->placeholder('Rp 0')
                                    ->helperText('Komisi ke affiliate'),
                            ]),
                        
                        TextEntry::make('formula')
                            ->label('')
                            ->state(fn ($record) => sprintf(
                                'Formula: (Subtotal Rp %s - Discount Rp %s + Payment Fee Rp %s) - Affiliate Commission Rp %s = Net Revenue Rp %s',
                                number_format($record->subtotal),
                                number_format($record->discount),
                                number_format($record->payment_fee),
                                number_format($record->commission->first()?->commission_amount ?? 0),
                                number_format($record->total - ($record->commission->first()?->commission_amount ?? 0))
                            ))
                            ->color('gray')
                            ->extraAttributes(['class' => 'text-xs']),
                    ])
                    ->description('Total Paid - Affiliate Commission = Net Revenue')
                    ->columnSpan(12),
            ]);
    }
}
