<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TutorResource\Pages;
use App\Models\Tutor;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Str;

class TutorResource extends Resource
{
    protected static ?string $model = Tutor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static string|\UnitEnum|null $navigationGroup = 'Course Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        // Main Content - 2 columns
                        Section::make('Tutor Information')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                TextInput::make('title')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Senior Instructor, Lead Developer')
                                    ->helperText('Professional title or role'),

                                TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),

                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(20),

                                TextInput::make('experience_years')
                                    ->label('Years of Experience')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(50)
                                    ->default(0)
                                    ->suffix('years'),

                                Textarea::make('bio')
                                    ->rows(4)
                                    ->maxLength(1000)
                                    ->columnSpanFull()
                                    ->helperText('Short biography about the tutor'),
                            ])
                            ->columnSpan(2),

                        // Sidebar - 1 column
                        Grid::make(1)
                            ->schema([
                                Section::make('Photo')
                                    ->schema([
                                        FileUpload::make('avatar')
                                            ->image()
                                            ->disk('public')
                                            ->directory('tutors')
                                            ->imageEditor()
                                            ->avatar()
                                            ->circleCropper()
                                            ->maxSize(2048),
                                    ]),

                                Section::make('Settings')
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(true)
                                            ->helperText('Inactive tutors won\'t appear on the site'),

                                        TextInput::make('order')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->helperText('Lower number = higher priority'),
                                    ]),

                                Section::make('Social Links')
                                    ->schema([
                                        TextInput::make('website')
                                            ->url()
                                            ->placeholder('https://...')
                                            ->prefixIcon('heroicon-o-globe-alt'),

                                        TextInput::make('linkedin')
                                            ->url()
                                            ->placeholder('https://linkedin.com/in/...')
                                            ->prefixIcon('heroicon-o-link'),

                                        TextInput::make('youtube')
                                            ->url()
                                            ->placeholder('https://youtube.com/@...')
                                            ->prefixIcon('heroicon-o-play'),

                                        TextInput::make('instagram')
                                            ->url()
                                            ->placeholder('https://instagram.com/...')
                                            ->prefixIcon('heroicon-o-camera'),
                                    ])
                                    ->collapsible(),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('#')
                    ->sortable()
                    ->width(50),

                ImageColumn::make('avatar')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn (Tutor $record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=f97316&color=fff'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Tutor $record) => $record->title),

                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('experience_years')
                    ->label('Experience')
                    ->formatStateUsing(fn ($state) => $state . ' years')
                    ->sortable(),

                TextColumn::make('courses_count')
                    ->counts('courses')
                    ->label('Courses')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->recordActions([
                ViewAction::make(),
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
            'index' => Pages\ListTutors::route('/'),
            'create' => Pages\CreateTutor::route('/create'),
            'view' => Pages\ViewTutor::route('/{record}'),
            'edit' => Pages\EditTutor::route('/{record}/edit'),
        ];
    }
}
