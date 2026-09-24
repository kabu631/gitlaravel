<?php

namespace App\Filament\Resources\GadgetResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SpecsRelationManager extends RelationManager
{
    protected static string $relationship = 'specs';
    protected static ?string $title = 'Specifications';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('display')->nullable(),
            TextInput::make('processor')->nullable(),
            TextInput::make('ram')->nullable()->label('RAM'),
            TextInput::make('storage')->nullable(),
            TextInput::make('battery')->nullable(),
            TextInput::make('camera')->nullable(),
            TextInput::make('os')->nullable()->label('OS'),
            TextInput::make('connectivity')->nullable(),
            TextInput::make('weight')->nullable(),
            TextInput::make('dimensions')->nullable(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('processor'),
            TextColumn::make('ram')->label('RAM'),
            TextColumn::make('storage'),
            TextColumn::make('battery'),
        ])->headerActions([
            CreateAction::make()->hidden(fn () => $this->getOwnerRecord()->specs()->exists()),
        ])->actions([
\Filament\Actions\ActionGroup::make([EditAction::make()]),
]);
    }
}
