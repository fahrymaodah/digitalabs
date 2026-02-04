<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use App\Models\CourseCategory;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|\UnitEnum|null $navigationGroup = 'Course Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Course')
                    ->tabs([
                        Tabs\Tab::make('Course Info')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        // Main Content - 2 columns
                                        Section::make('Course Information')
                                            ->schema([
                                                TextInput::make('title')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                                                TextInput::make('slug')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->unique(ignoreRecord: true),

                                                Textarea::make('description')
                                                    ->required()
                                                    ->rows(3)
                                                    ->maxLength(500)
                                                    ->helperText('Short description for listing'),

                                                RichEditor::make('content')
                                                    ->required()
                                                    ->toolbarButtons([
                                                        'bold',
                                                        'italic',
                                                        'underline',
                                                        'strike',
                                                        'h2',
                                                        'h3',
                                                        'bulletList',
                                                        'orderedList',
                                                        'link',
                                                    ])
                                                    ->columnSpanFull(),
                                            ])
                                            ->columnSpan(2),

                                        // Sidebar - 1 column
                                        Grid::make(1)
                                            ->schema([
                                                Section::make('Details')
                                                    ->schema([
                                                        Select::make('category_id')
                                                            ->label('Category')
                                                            ->options(CourseCategory::pluck('name', 'id'))
                                                            ->required()
                                                            ->searchable(),

                                                        Select::make('status')
                                                            ->options([
                                                                'draft' => 'Draft',
                                                                'published' => 'Published',
                                                                'archived' => 'Archived',
                                                            ])
                                                            ->default('draft')
                                                            ->required(),

                                                        Select::make('access_type')
                                                            ->options([
                                                                'lifetime' => 'Lifetime Access',
                                                                'limited' => 'Limited Time',
                                                            ])
                                                            ->default('lifetime')
                                                            ->required()
                                                            ->live(),

                                                        TextInput::make('access_days')
                                                            ->numeric()
                                                            ->minValue(1)
                                                            ->suffix('days')
                                                            ->visible(fn ($get) => $get('access_type') === 'limited'),

                                                        TextInput::make('order')
                                                            ->numeric()
                                                            ->default(0)
                                                            ->minValue(0),
                                                    ]),

                                                Section::make('Pricing')
                                                    ->schema([
                                                        TextInput::make('price')
                                                            ->required()
                                                            ->numeric()
                                                            ->prefix('Rp')
                                                            ->minValue(0),

                                                        TextInput::make('sale_price')
                                                            ->numeric()
                                                            ->prefix('Rp')
                                                            ->minValue(0)
                                                            ->helperText('Leave empty for no sale'),
                                                    ]),

                                                Section::make('Media')
                                                    ->schema([
                                                        FileUpload::make('thumbnail')
                                                            ->image()
                                                            ->disk('public')
                                                            ->directory('courses/thumbnails')
                                                            ->imageEditor()
                                                            ->maxSize(2048),

                                                        TextInput::make('preview_url')
                                                            ->label('Preview Video URL')
                                                            ->url()
                                                            ->placeholder('https://youtube.com/...'),
                                                    ]),
                                            ])
                                            ->columnSpan(1),
                                    ]),
                            ]),

                        Tabs\Tab::make('Topics & Lessons')
                            ->icon('heroicon-o-rectangle-stack')
                            ->schema([
                                Repeater::make('topics')
                                    ->relationship()
                                    ->label('Course Topics')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('title')
                                                    ->label('Topic Title')
                                                    ->required()
                                                    ->maxLength(255),

                                                TextInput::make('order')
                                                    ->label('Order')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->minValue(0),
                                            ]),

                                        Textarea::make('description')
                                            ->label('Topic Description')
                                            ->rows(2),

                                        Repeater::make('lessons')
                                            ->relationship()
                                            ->label('Lessons')
                                            ->schema([
                                                Grid::make(4)
                                                    ->schema([
                                                        TextInput::make('title')
                                                            ->label('Lesson Title')
                                                            ->required()
                                                            ->maxLength(255)
                                                            ->columnSpan(2),

                                                        TextInput::make('order')
                                                            ->label('Order')
                                                            ->numeric()
                                                            ->default(0)
                                                            ->minValue(0),

                                                        Toggle::make('is_free')
                                                            ->label('Free Preview')
                                                            ->default(false),
                                                    ]),

                                                Grid::make(2)
                                                    ->schema([
                                                        TextInput::make('youtube_url')
                                                            ->label('YouTube URL')
                                                            ->required()
                                                            ->url()
                                                            ->placeholder('https://youtube.com/watch?v=...'),

                                                        TextInput::make('duration')
                                                            ->label('Duration (minutes)')
                                                            ->numeric()
                                                            ->minValue(0)
                                                            ->suffix('min'),
                                                    ]),
                                            ])
                                            ->orderColumn('order')
                                            ->reorderable()
                                            ->collapsible()
                                            ->collapsed()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'New Lesson')
                                            ->addActionLabel('Add Lesson')
                                            ->defaultItems(0),
                                    ])
                                    ->orderColumn('order')
                                    ->reorderable()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'New Topic')
                                    ->addActionLabel('Add Topic')
                                    ->defaultItems(0)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
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

                ImageColumn::make('thumbnail')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://placehold.co/100x100?text=No+Image'),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Course $record) => Str::limit($record->description, 35)),

                TextColumn::make('category.name')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable()
                    ->description(fn (Course $record) => $record->sale_price ? 'Sale: Rp ' . number_format($record->sale_price, 0, ',', '.') : null),

                TextColumn::make('topics_count')
                    ->counts('topics')
                    ->label('T / L')
                    ->formatStateUsing(function (Course $record) {
                        $topics = $record->topics_count ?? 0;
                        $lessons = $record->topics->sum(fn ($topic) => $topic->lessons->count()) ?? 0;
                        return "$topics / $lessons";
                    })
                    ->tooltip(function (Course $record) {
                        $topics = $record->topics_count ?? 0;
                        $lessons = $record->topics->sum(fn ($topic) => $topic->lessons->count()) ?? 0;
                        return "$topics Topics / $lessons Lessons";
                    })
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'danger',
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'view' => Pages\ViewCourse::route('/{record}'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
