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
            ->components([
                Grid::make(2)
                    ->schema([
                        Section::make('Order Information')
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
                            ])
                            ->columns(2),

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
                            ->columns(2),
                    ]),

                Section::make('Price Breakdown')
                    ->schema([
                        Grid::make(5)
                            ->schema([
                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('IDR')
                                    ->helperText('Harga produk'),

                                TextEntry::make('coupon.code')
                                    ->label('Coupon')
                                    ->badge()
                                    ->color('success')
                                    ->placeholder('No coupon')
                                    ->suffix(fn ($record) => $record->coupon 
                                        ? " ({$record->coupon->value}%)" 
                                        : null),

                                TextEntry::make('discount')
                                    ->label('Discount')
                                    ->money('IDR')
                                    ->color('danger')
                                    ->prefix('- ')
                                    ->placeholder('Rp 0'),

                                TextEntry::make('payment_fee')
                                    ->label('Payment Fee')
                                    ->money('IDR')
                                    ->color('warning')
                                    ->prefix('+ ')
                                    ->helperText('Unique code / gateway fee'),

                                TextEntry::make('total')
                                    ->label('Total Paid')
                                    ->money('IDR')
                                    ->weight('bold')
                                    ->size('lg')
                                    ->color('success'),
                            ]),
                        
                        // Formula verification
                        TextEntry::make('formula')
                            ->label('')
                            ->state(fn ($record) => sprintf(
                                'Formula: Rp %s - Rp %s + Rp %s = Rp %s',
                                number_format($record->subtotal),
                                number_format($record->discount),
                                number_format($record->payment_fee),
                                number_format($record->subtotal - $record->discount + $record->payment_fee)
                            ))
                            ->color('gray')
                            ->extraAttributes(['class' => 'text-xs']),
                    ])
                    ->description('Subtotal - Discount + Payment Fee = Total'),

                Section::make('Order Items')
                    ->schema([
                        RepeatableEntry::make('items')
                            ->schema([
                                TextEntry::make('course.title')
                                    ->label('Course'),
                                TextEntry::make('original_price')
                                    ->label('Original Price')
                                    ->money('IDR')
                                    ->color('gray')
                                    ->strikethrough(fn ($record) => $record->price < ($record->original_price ?? $record->price)),
                                TextEntry::make('price')
                                    ->label('Purchase Price')
                                    ->money('IDR')
                                    ->weight('bold'),
                                TextEntry::make('discount_percentage')
                                    ->label('Savings')
                                    ->badge()
                                    ->color('success')
                                    ->suffix('%')
                                    ->placeholder('-')
                                    ->formatStateUsing(fn ($state) => $state > 0 ? "{$state}% off" : null),
                            ])
                            ->columns(4),
                    ])
                    ->description('Courses purchased in this order'),

                Section::make('Affiliate')
                    ->schema([
                        TextEntry::make('affiliate.user.name')
                            ->label('Affiliate')
                            ->placeholder('No affiliate'),
                        TextEntry::make('affiliate.referral_code')
                            ->label('Referral Code')
                            ->placeholder('N/A'),
                        TextEntry::make('commission.0.amount')
                            ->label('Commission Amount')
                            ->money('IDR')
                            ->placeholder('N/A'),
                    ])
                    ->columns(3)
                    ->visible(fn ($record) => $record->affiliate_id !== null),
            ]);
    }
}
