<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Order Items';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('discount')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('final_price')
                    ->label('Final Price')
                    ->money('IDR')
                    ->state(fn ($record) => $record->price - $record->discount),
            ]);
    }
}
