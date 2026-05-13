<?php

namespace App\Filament\Resources\GadgetResource\RelationManagers;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            FileUpload::make('image')->image()->disk('public')->directory('gadgets/gallery')->required(),
            TextInput::make('alt_text')->maxLength(255)->nullable(),
            TextInput::make('order')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('image')->disk('public'),
            TextColumn::make('alt_text'),
            TextColumn::make('order')->sortable(),
        ])->headerActions([CreateAction::make()])
          ->actions([EditAction::make()])
          ->bulkActions([DeleteBulkAction::make()])
          ->defaultSort('order');
    }
}
