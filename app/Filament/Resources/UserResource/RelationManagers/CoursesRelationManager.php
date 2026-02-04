<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'userCourses';

    protected static ?string $title = 'Owned Courses';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('purchased_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Lifetime'),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->state(fn ($record) => $record->is_active ? 'Active' : 'Expired')
                    ->color(fn ($record) => $record->is_active ? 'success' : 'danger'),
            ])
            ->defaultSort('purchased_at', 'desc');
    }
}
