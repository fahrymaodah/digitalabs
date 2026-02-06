<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Models\Order;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static string|\UnitEnum|null $navigationGroup = 'E-Commerce';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'order_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Information')
                    ->schema([
                        TextInput::make('order_number')
                            ->disabled(),

                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->disabled(),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'expired' => 'Expired',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),

                        TextInput::make('total')
                            ->disabled()
                            ->prefix('Rp'),

                        TextInput::make('payment_method')
                            ->disabled(),

                        TextInput::make('duitku_reference')
                            ->label('Duitku Reference')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order & Customer')
                    ->formatStateUsing(fn($state, $record) => 
                        '<strong>' . $state . '</strong><br>' . $record->user->name
                    )
                    ->html()
                    ->searchable('order_number')
                    ->sortable('order_number')
                    ->copyable(fn($state) => $state),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                    // ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('discount')
                    ->label('Discount')
                    ->formatStateUsing(fn($state, $record) => 
                        ($state == 0 && !$record->coupon) 
                            ? '-' 
                            : ('IDR ' . number_format($state, 0, ',', '.') . '<br>' . 
                               ($record->coupon ? '<span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 inset-ring inset-ring-green-500/20">' . strtoupper($record->coupon->code) . '</span>' : '-'))
                    )
                    ->html()
                    ->alignment('center')
                    ->color('danger')
                    ->toggleable(),

                TextColumn::make('payment_fee')
                    ->label('Fee')
                    ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state, 0, ',', '.'))
                    ->color('warning')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('total')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => 'IDR ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('payment_method')
                    ->label('Payment')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'duitku' => 'info',
                        'bank_transfer' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'duitku' => 'Duitku',
                        'bank_transfer' => 'Bank Transfer',
                        default => $state ?? '-',
                    })
                    ->toggleable(),

                TextColumn::make('paid_at')
                    ->label('Paid At')
                    ->formatStateUsing(fn($state) => $state ? $state->format('d M Y') . '<br>' . $state->format('H:i') : '-')
                    ->html()
                    ->sortable()
                    ->placeholder('Not paid')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->formatStateUsing(fn($state) => $state ? $state->format('d M Y') . '<br>' . $state->format('H:i') : '-')
                    ->html()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('payment_method')
                    ->options([
                        'duitku' => 'Duitku (Online)',
                        'bank_transfer' => 'Bank Transfer (Manual)',
                    ]),
                SelectFilter::make('coupon_id')
                    ->label('Coupon Used')
                    ->relationship('coupon', 'code'),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Orders are created through checkout
    }
}
