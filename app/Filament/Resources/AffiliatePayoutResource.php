<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliatePayoutResource\Pages;
use App\Models\AffiliatePayout;
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
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

class AffiliatePayoutResource extends Resource
{
    protected static ?string $model = AffiliatePayout::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|\UnitEnum|null $navigationGroup = 'Affiliate';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Payout';

    protected static ?string $pluralModelLabel = 'Payouts';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make('Payout Information')
                    ->schema([
                        Select::make('affiliate_id')
                            ->relationship('affiliate', 'referral_code')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->user->name} ({$record->referral_code})")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(6),

                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->columnSpan(3),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                            ])
                            ->default('pending')
                            ->required()
                            ->columnSpan(3),

                        Textarea::make('notes')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(12)
                    ->columnSpan(8),

                Section::make('Bank Details')
                    ->schema([
                        TextInput::make('bank_name')
                            ->maxLength(100),

                        TextInput::make('bank_account_number')
                            ->maxLength(50),

                        TextInput::make('bank_account_name')
                            ->maxLength(100),
                    ])
                    ->columns(1)
                    ->columnSpan(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('affiliate.user.name')
                    ->label('Affiliate')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('affiliate.referral_code')
                    ->label('Code')
                    ->badge()
                    ->color('info'),

                TextColumn::make('amount')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('bank_name')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Requested At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
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
            // No direct relation manager needed
            // Commissions are linked through affiliate
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAffiliatePayouts::route('/'),
            'create' => Pages\CreateAffiliatePayout::route('/create'),
            'view' => Pages\ViewAffiliatePayout::route('/{record}'),
            'edit' => Pages\EditAffiliatePayout::route('/{record}/edit'),
        ];
    }
}
