<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewArticle extends ViewRecord
{
    protected static string $resource = ArticleResource::class;

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
                Grid::make(3)
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Section::make('Article Content')
                                    ->schema([
                                        TextEntry::make('title'),
                                        TextEntry::make('slug'),
                                        TextEntry::make('excerpt'),
                                        TextEntry::make('content')
                                            ->html()
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpan(2),

                        Grid::make(1)
                            ->schema([
                                Section::make('Details')
                                    ->schema([
                                        TextEntry::make('category.name')
                                            ->badge(),
                                        TextEntry::make('is_published')
                                            ->label('Status')
                                            ->badge()
                                            ->formatStateUsing(fn ($state) => $state ? 'Published' : 'Draft')
                                            ->color(fn ($state) => $state ? 'success' : 'warning'),
                                        TextEntry::make('published_at')
                                            ->dateTime(),
                                        TextEntry::make('created_at')
                                            ->dateTime(),
                                    ]),

                                Section::make('Featured Image')
                                    ->schema([
                                        ImageEntry::make('featured_image'),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
