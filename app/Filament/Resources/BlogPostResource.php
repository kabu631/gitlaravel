<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-pencil-square';
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Blog Posts';
    protected static ?int $navigationSort = 3;

    public const CATEGORIES = [
        'general'    => 'General',
        'tips'       => 'Tips & Tricks',
        'how-to'     => 'How-To',
        'opinion'    => 'Opinion',
        'deals'      => 'Deals & Offers',
        'lifestyle'  => 'Tech Lifestyle',
        'behind'     => 'Behind the Scenes',
    ];

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Post')->columnSpanFull()->schema([
                TextInput::make('title')->required()->maxLength(255)->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set, $get) {
                        if (! $get('slug')) {
                            $set('slug', Str::slug($state));
                        }
                    })->columnSpanFull(),
                TextInput::make('slug')->maxLength(255)->unique(ignoreRecord: true)
                    ->helperText('Auto-generated from the title if left empty'),
                Select::make('category')->options(self::CATEGORIES)->default('general')->required(),
                Textarea::make('excerpt')->rows(2)->maxLength(300)->columnSpanFull()
                    ->helperText('Short summary shown on listing cards'),
                RichEditor::make('content')->required()->columnSpanFull(),
                FileUpload::make('cover_image')->image()->disk('public')->directory('blog')->nullable(),
                TagsInput::make('tags')->placeholder('Add a tag'),
                TextInput::make('meta_description')->maxLength(300)->columnSpanFull(),
            ])->columns(2),

            Section::make('Publishing')->columnSpanFull()->schema([
                Select::make('user_id')->label('Author')->relationship('author', 'name')
                    ->default(fn () => auth()->id())->searchable()->preload()->nullable(),
                DateTimePicker::make('published_at')->helperText('Empty = set automatically on publish. A future date schedules the post.'),
                Toggle::make('is_published')->label('Published')->default(true),
                Toggle::make('is_featured')->label('Featured on homepage & blog hero'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('cover_image')->disk('public')->height(40),
            TextColumn::make('title')->searchable()->limit(50)->weight('bold'),
            TextColumn::make('category')->badge()->formatStateUsing(fn ($s) => self::CATEGORIES[$s] ?? $s),
            TextColumn::make('author.name')->label('Author'),
            TextColumn::make('views_count')->label('Views')->sortable(),
            IconColumn::make('is_featured')->boolean()->label('Featured'),
            IconColumn::make('is_published')->boolean()->label('Published'),
            TextColumn::make('published_at')->dateTime('d M Y')->sortable(),
        ])
            ->filters([TernaryFilter::make('is_published')->label('Published')])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('view')->label('View')->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn ($record) => route('blog.show', $record->slug))->openUrlInNewTab(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit'   => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
