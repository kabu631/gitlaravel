<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsArticleResource\Pages;
use App\Models\NewsArticle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use BackedEnum;
use UnitEnum;

class NewsArticleResource extends Resource
{
    protected static ?string $model = NewsArticle::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-newspaper';
    protected static UnitEnum|string|null $navigationGroup = 'Content';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('News Article Information')->columnSpanFull()->schema([
                TextInput::make('title')->required()->maxLength(255)->live(onBlur: true)
                    ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug($state)))->columnSpanFull(),
                TextInput::make('slug')->required()->maxLength(255),
                Select::make('category')->options([
                    // Keys must match the URL ?category= values used in AppLayout nav + NewsController
                    'technology'   => 'Technology',
                    'rumors'       => 'Rumors & Upcoming Launches',
                    'mobile'       => 'Mobile Launches',
                    'laptop'       => 'Laptops',
                    'gaming'       => 'Gaming',
                    'ai-ml'        => 'AI & Innovations',
                    'software'     => 'Software',
                    'gadgets'      => 'Gadgets',
                    'telecom'      => 'Telecom & 5G',
                    'gpu'          => 'GPUs & Hardware',
                    'price-trends' => 'Price Trends & Hikes',
                    'sci-fi'       => 'Sci-Fi Cinema Tech',
                ])->required(),
                Select::make('user_id')
                    ->label('Author')
                    ->relationship('author', 'name')
                    ->default(fn() => auth()->id())
                    ->searchable()
                    ->preload()
                    ->nullable(),
                FileUpload::make('thumbnail')->image()->disk('public')->directory('news')->nullable(),
                TextInput::make('meta_description')->maxLength(160)->nullable()->columnSpanFull(),
                RichEditor::make('content')->required()->columnSpanFull(),
                Toggle::make('is_published')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->searchable()->limit(40),
            TextColumn::make('author.name')->label('Author')->sortable(),
            TextColumn::make('category')->badge()->color(fn($state) => match($state) {
                'technology' => 'info', 'mobile' => 'primary', 'gaming' => 'success',
                'ai-ml' => 'warning', 'laptop' => 'gray', default => 'secondary',
            }),
            TextColumn::make('views_count')->label('Views')->sortable(),
            IconColumn::make('is_published')->boolean()->label('Published'),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ])->filters([
            SelectFilter::make('category')->options([
                'technology' => 'Technology', 'mobile' => 'Mobile Launches',
                'laptop' => 'Laptops', 'gaming' => 'Gaming',
                'ai-ml' => 'AI & Innovations', 'software' => 'Software',
                'gadgets' => 'Gadgets', 'telecom' => 'Telecom',
            ]),
            TernaryFilter::make('is_published')->label('Published'),
        ])->actions([
\Filament\Actions\ActionGroup::make([
            EditAction::make(),
            Action::make('view')
                ->label('View')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn($record) => route('news.show', $record->slug))
                ->openUrlInNewTab(),
\Filament\Actions\DeleteAction::make(),
]),
])
          ->bulkActions([DeleteBulkAction::make()])
          ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNewsArticles::route('/'),
            'create' => Pages\CreateNewsArticle::route('/create'),
            'edit'   => Pages\EditNewsArticle::route('/{record}/edit'),
        ];
    }
}
