<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Models\Coupon;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Support\Str;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static string|\UnitEnum|null $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make('Coupon Details')
                    ->schema([
                        TextInput::make('code')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->placeholder('DIGITALABS')
                            ->helperText('Uppercase, no spaces')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('code', strtoupper(Str::slug($state, '')))),

                        Textarea::make('description')
                            ->rows(2)
                            ->maxLength(255)
                            ->placeholder('Discount for new users'),

                        Grid::make(2)
                            ->schema([
                                Select::make('type')
                                    ->options([
                                        'percentage' => 'Percentage (%)',
                                        'fixed' => 'Fixed Amount (Rp)',
                                    ])
                                    ->default('percentage')
                                    ->required()
                                    ->live(),

                                TextInput::make('value')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix(fn ($get) => $get('type') === 'percentage' ? '%' : 'Rp')
                                    ->helperText(fn ($get) => $get('type') === 'percentage' ? 'Enter 0-100' : 'Fixed discount amount'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('min_order_amount')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->minValue(0)
                                    ->placeholder('0')
                                    ->helperText('Minimum order to apply coupon'),

                                TextInput::make('max_discount')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->minValue(0)
                                    ->placeholder('No limit')
                                    ->helperText('Max discount for percentage')
                                    ->visible(fn ($get) => $get('type') === 'percentage'),
                            ]),
                    ])
                    ->columnSpan(6),

                Section::make('Usage Limits')
                    ->schema([
                        TextInput::make('usage_limit')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('Unlimited')
                            ->helperText('Total times coupon can be used'),

                        TextInput::make('usage_limit_per_user')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required()
                            ->helperText('Per user limit'),

                        TextInput::make('used_count')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Times used so far'),
                    ])
                    ->columnSpan(3),

                Section::make('Validity Period')
                    ->schema([
                        DateTimePicker::make('starts_at')
                            ->label('Start Date')
                            ->placeholder('Immediately'),

                        DateTimePicker::make('expires_at')
                            ->label('Expiry Date')
                            ->placeholder('Never expires')
                            ->after('starts_at'),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Enable or disable coupon'),
                    ])
                    ->columnSpan(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'percentage' => 'info',
                        'fixed' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'percentage' => 'Percentage',
                        'fixed' => 'Fixed',
                    }),

                TextColumn::make('formatted_value')
                    ->label('Discount')
                    ->sortable(query: fn ($query, $direction) => $query->orderBy('value', $direction)),

                TextColumn::make('used_count')
                    ->label('Used')
                    ->formatStateUsing(function (Coupon $record) {
                        if ($record->usage_limit) {
                            return "{$record->used_count} / {$record->usage_limit}";
                        }
                        return $record->used_count;
                    })
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('d M Y')
                    ->placeholder('Never')
                    ->sortable()
                    ->color(fn (Coupon $record) => $record->expires_at?->isPast() ? 'danger' : null),

                IconColumn::make('is_valid')
                    ->label('Valid')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Status'),
                SelectFilter::make('type')
                    ->options([
                        'percentage' => 'Percentage',
                        'fixed' => 'Fixed Amount',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }
}
