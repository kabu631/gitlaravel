<?php

namespace App\Filament\Resources\GadgetResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PriceHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'priceHistory';
    protected static ?string $title = 'Price History';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('price')->numeric()->prefix('NPR')->required(),
            TextInput::make('date')->type('date')->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('date')->date()->sortable(),
            TextColumn::make('price')->money('NPR'),
        ])->headerActions([CreateAction::make()])
          ->bulkActions([DeleteBulkAction::make()])
          ->defaultSort('date', 'desc');
    }
}
