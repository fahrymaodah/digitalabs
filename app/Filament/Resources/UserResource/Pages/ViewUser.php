<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make('User Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name'),
                                TextEntry::make('email')
                                    ->copyable(),
                                TextEntry::make('phone')
                                    ->placeholder('No phone'),
                                TextEntry::make('created_at')
                                    ->dateTime(),
                                TextEntry::make('email_verified_at')
                                    ->label('Email Verified')
                                    ->dateTime()
                                    ->placeholder('Not verified'),
                                IconEntry::make('is_admin')
                                    ->label('Administrator')
                                    ->boolean(),
                            ])
                    ])
                    ->columnSpan(4),

                Section::make('Avatar')
                    ->schema([
                        ImageEntry::make('avatar')
                            ->hiddenLabel()
                            ->width(150)
                            ->height(150)
                            ->disk('public')
                            ->circular()
                            ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name))
                            ->alignment('center'),
                    ])
                    ->columnSpan(2),
            ]);
    }
}
