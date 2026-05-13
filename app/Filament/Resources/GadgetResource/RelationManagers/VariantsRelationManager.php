<?php

namespace App\Filament\Resources\GadgetResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('variant_type')->options([
                'ram' => 'RAM', 'color' => 'Color', 'storage' => 'Storage',
                'screen_size' => 'Screen Size', 'connectivity' => 'Connectivity', 'other' => 'Other',
            ])->required(),
            TextInput::make('value')->required()->maxLength(100)->label('Value (e.g. 16GB, Black)'),
            TextInput::make('price')->numeric()->prefix('NPR')->nullable()->label('Absolute Price (NPR)'),
            TextInput::make('stock')->numeric()->default(0),
            Toggle::make('is_available')->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('variant_type')->badge(),
            TextColumn::make('value'),
            TextColumn::make('price')->money('NPR')->placeholder('— base price'),
            TextColumn::make('stock'),
            IconColumn::make('is_available')->boolean()->label('Available'),
        ])->headerActions([CreateAction::make()])
          ->actions([EditAction::make()])
          ->bulkActions([DeleteBulkAction::make()]);
    }
}
