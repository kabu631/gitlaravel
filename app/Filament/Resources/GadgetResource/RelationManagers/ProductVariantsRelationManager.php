<?php

namespace App\Filament\Resources\GadgetResource\RelationManagers;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductVariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'productVariants';
    protected static ?string $title = 'Product Variants (SKUs)';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('sku')
                ->label('SKU Code')
                ->maxLength(100)
                ->unique(table: 'product_variants', column: 'sku', ignoreRecord: true)
                ->nullable()
                ->placeholder('e.g. IPH17PM-SLV-12-256'),

            TextInput::make('color')
                ->maxLength(100)
                ->nullable()
                ->placeholder('e.g. Titanium Black'),

            TextInput::make('ram')
                ->label('RAM')
                ->maxLength(50)
                ->nullable()
                ->placeholder('e.g. 12GB'),

            TextInput::make('storage')
                ->maxLength(50)
                ->nullable()
                ->placeholder('e.g. 256GB'),

            TextInput::make('size')
                ->maxLength(50)
                ->nullable()
                ->placeholder('e.g. 6.7 inch'),

            TextInput::make('price')
                ->numeric()
                ->prefix('NPR')
                ->required()
                ->placeholder('e.g. 248999'),

            TextInput::make('discounted_price')
                ->numeric()
                ->prefix('NPR')
                ->nullable()
                ->label('Sale Price (optional)')
                ->placeholder('Leave empty if no sale'),

            TextInput::make('stock_quantity')
                ->numeric()
                ->default(0)
                ->required()
                ->label('Stock Quantity'),

            FileUpload::make('variant_image')
                ->image()
                ->disk('public')
                ->directory('gadgets/variants')
                ->nullable()
                ->label('Variant Image (optional)'),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('variant_image')
                    ->disk('public')
                    ->square()
                    ->size(52)
                    ->label('Image')
                    ->defaultImageUrl(asset('placeholder.png')),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->placeholder('—')
                    ->copyable(),

                TextColumn::make('color')
                    ->placeholder('—'),

                TextColumn::make('ram')
                    ->label('RAM')
                    ->placeholder('—'),

                TextColumn::make('storage')
                    ->placeholder('—'),

                TextColumn::make('size')
                    ->placeholder('—'),

                TextColumn::make('price')
                    ->money('NPR')
                    ->sortable(),

                TextColumn::make('discounted_price')
                    ->money('NPR')
                    ->placeholder('—')
                    ->label('Sale Price'),

                TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->sortable()
                    ->color(fn($record) => $record->stock_quantity < 1 ? 'danger' : ($record->stock_quantity <= 5 ? 'warning' : 'success')),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->headerActions([
                CreateAction::make()->label('Add Variant'),
            ])
            ->actions([
\Filament\Actions\ActionGroup::make([
                EditAction::make(),
                DeleteAction::make(),
            ]),
])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('price');
    }
}
