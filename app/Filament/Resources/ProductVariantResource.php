<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductVariantResource\Pages;
use App\Models\ProductVariant;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class ProductVariantResource extends Resource
{
    protected static ?string $model = ProductVariant::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static UnitEnum|string|null $navigationGroup = 'Catalog';
    protected static ?string $navigationLabel = 'Product Variants';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Product Variant Information')->columnSpanFull()->schema([
                Select::make('gadget_id')
                    ->label('Gadget / Product')
                    ->relationship('gadget', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

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
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('gadget.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable()
                    ->limit(30),

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
            ->filters([
                SelectFilter::make('gadget')
                    ->relationship('gadget', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Product'),
                TernaryFilter::make('is_active')->label('Active'),
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
            ->defaultSort('gadget_id');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProductVariants::route('/'),
            'create' => Pages\CreateProductVariant::route('/create'),
            'edit'   => Pages\EditProductVariant::route('/{record}/edit'),
        ];
    }
}
