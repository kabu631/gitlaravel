<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsArticleResource\Pages;
use App\Models\NewsArticle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
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
            TextInput::make('title')->required()->maxLength(255)->live(onBlur: true)
                ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug($state)))->columnSpanFull(),
            TextInput::make('slug')->required()->maxLength(255),
            Select::make('category')->options([
                'tech' => 'Technology', 'mobile' => 'Mobile', 'laptop' => 'Laptop',
                'gaming' => 'Gaming', 'ai' => 'AI & ML', 'software' => 'Software',
                'gadgets' => 'Gadgets', 'telecom' => 'Telecom',
            ])->required(),
            FileUpload::make('thumbnail')->image()->disk('public')->directory('news')->nullable(),
            TextInput::make('meta_description')->maxLength(160)->nullable()->columnSpanFull(),
            RichEditor::make('content')->required()->columnSpanFull(),
            Toggle::make('is_published')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->searchable()->limit(40),
            TextColumn::make('category')->badge(),
            TextColumn::make('views_count')->label('Views'),
            IconColumn::make('is_published')->boolean(),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ])->actions([EditAction::make()])
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
