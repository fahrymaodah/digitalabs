<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Section::make('User Information')
                            ->schema([
                                TextEntry::make('name'),
                                TextEntry::make('email')
                                    ->copyable(),
                                TextEntry::make('phone')
                                    ->placeholder('No phone'),
                                TextEntry::make('created_at')
                                    ->dateTime(),
                            ])
                            ->columnSpan(2),

                        Section::make('Account')
                            ->schema([
                                ImageEntry::make('avatar')
                                    ->disk('public')
                                    ->circular()
                                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name)),
                                IconEntry::make('is_admin')
                                    ->label('Administrator')
                                    ->boolean(),
                                TextEntry::make('email_verified_at')
                                    ->label('Email Verified')
                                    ->dateTime()
                                    ->placeholder('Not verified'),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
