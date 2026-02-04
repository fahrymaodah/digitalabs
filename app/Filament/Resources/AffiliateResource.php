<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateResource\Pages;
use App\Filament\Resources\AffiliateResource\RelationManagers\CommissionsRelationManager;
use App\Filament\Resources\AffiliateResource\RelationManagers\OrdersRelationManager;
use App\Models\Affiliate;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

class AffiliateResource extends Resource
{
    protected static ?string $model = Affiliate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|\UnitEnum|null $navigationGroup = 'Affiliate';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'referral_code';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Affiliate Information')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required(),
                                TextInput::make('email')
                                    ->email()
                                    ->required(),
                                TextInput::make('password')
                                    ->password()
                                    ->required(),
                            ]),

                        TextInput::make('referral_code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->helperText('Unique code for tracking referrals'),

                        TextInput::make('commission_rate')
                            ->numeric()
                            ->required()
                            ->default(10)
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->helperText('Commission percentage for each sale'),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending Review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'suspended' => 'Suspended',
                            ])
                            ->default('pending')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Earnings')
                    ->schema([
                        TextInput::make('total_earnings')
                            ->prefix('Rp')
                            ->disabled()
                            ->default(0),

                        TextInput::make('pending_earnings')
                            ->prefix('Rp')
                            ->disabled()
                            ->default(0),

                        TextInput::make('paid_earnings')
                            ->prefix('Rp')
                            ->disabled()
                            ->default(0),
                    ])
                    ->columns(3)
                    ->visibleOn('view'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Affiliate')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('referral_code')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('commission_rate')
                    ->suffix('%')
                    ->sortable(),

                TextColumn::make('orders_count')
                    ->counts('orders')
                    ->label('Referrals')
                    ->sortable(),

                TextColumn::make('total_earnings')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('pending_earnings')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'active' => 'success',
                        'suspended' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CommissionsRelationManager::class,
            OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAffiliates::route('/'),
            'create' => Pages\CreateAffiliate::route('/create'),
            'view' => Pages\ViewAffiliate::route('/{record}'),
            'edit' => Pages\EditAffiliate::route('/{record}/edit'),
        ];
    }
}
