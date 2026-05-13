<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechGuideResource\Pages;
use App\Models\TechGuide;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
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

class TechGuideResource extends Resource
{
    protected static ?string $model = TechGuide::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';
    protected static UnitEnum|string|null $navigationGroup = 'Content';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')->required()->maxLength(255)->live(onBlur: true)
                ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug($state)))->columnSpanFull(),
            TextInput::make('slug')->required()->maxLength(255),
            FileUpload::make('thumbnail')->image()->disk('public')->directory('guides')->nullable(),
            Toggle::make('is_published')->default(true),
            RichEditor::make('content')->required()->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->searchable()->limit(40),
            IconColumn::make('is_published')->boolean(),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ])->actions([EditAction::make()])
          ->bulkActions([DeleteBulkAction::make()])
          ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTechGuides::route('/'),
            'create' => Pages\CreateTechGuide::route('/create'),
            'edit'   => Pages\EditTechGuide::route('/{record}/edit'),
        ];
    }
}
