<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Ordered Products';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gadget.image')
                    ->label('Image')
                    ->square()
                    ->disk('public')
                    ->defaultImageUrl(asset('images/placeholder.png')),

                TextColumn::make('gadget.name')
                    ->label('Product')
                    ->searchable()
                    ->description(fn($record) => $record->gadget->brand?->name ?? ''),

                TextColumn::make('gadget.brand.name')
                    ->label('Brand'),

                TextColumn::make('variant_info')
                    ->label('Variant')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '—';
                        if (is_array($state)) {
                            return collect($state)->map(fn($v, $k) => "$k: $v")->implode(', ');
                        }
                        return $state;
                    })
                    ->placeholder('No variant'),

                TextColumn::make('price')
                    ->label('Unit Price')
                    ->formatStateUsing(fn($state) => 'NPR ' . number_format($state)),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->alignCenter(),

                TextColumn::make('subtotal')->sortable(false)
                    ->label('Subtotal')
                    ->state(fn($record) => 'NPR ' . number_format($record->quantity * $record->price))
                    ->weight('bold')
                    ->color('primary'),
            ])
            ->paginated(false);
    }
}
