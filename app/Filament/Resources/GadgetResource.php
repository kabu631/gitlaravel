<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GadgetResource\Pages;
use App\Filament\Resources\GadgetResource\RelationManagers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Gadget;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use BackedEnum;
use UnitEnum;

class GadgetResource extends Resource
{
    protected static ?string $model = Gadget::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-device-phone-mobile';
    protected static UnitEnum|string|null $navigationGroup = 'Catalog';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Basic Info')->schema([
                TextInput::make('name')->required()->maxLength(255)->live(onBlur: true)
                    ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')->required()->maxLength(255),
                Select::make('brand_id')->label('Brand')->relationship('brand', 'name')
                    ->searchable()->preload()->createOptionForm([
                        TextInput::make('name')->required(),
                    ])->required(),
                Select::make('category_id')->label('Category')->relationship('category', 'name')
                    ->searchable()->preload()->nullable(),
                Select::make('accessory_type')->options([
                    'monitor' => 'Monitor', 'mouse' => 'Mouse', 'keyboard' => 'Keyboard',
                    'headphone' => 'Headphone', 'printer' => 'Printer', 'pc_build' => 'PC Build',
                ])->nullable()->label('Accessory Type'),
            ])->columns(2),

            Section::make('Pricing')->schema([
                TextInput::make('price')->numeric()->prefix('NPR')->required(),
                TextInput::make('old_price')->numeric()->prefix('NPR')->nullable()->label('Old Price'),
            ])->columns(2),

            Section::make('Media')->schema([
                FileUpload::make('image')
                    ->label('Cover / Thumbnail Image')
                    ->image()
                    ->disk('public')
                    ->directory('gadgets')
                    ->imagePreviewHeight('150')
                    ->nullable()
                    ->helperText('This is the primary thumbnail shown in listings. Use the Images tab below to add a full gallery.'),
                FileUpload::make('model_3d')->disk('public')->directory('gadgets/3d')->nullable()->label('3D Model (.glb)'),
                TextInput::make('sketchfab_embed')->nullable()->label('Sketchfab Embed Code'),
            ]),

            Section::make('Details')->schema([
                RichEditor::make('description')->nullable()->columnSpanFull(),
                TextInput::make('release_date')->type('date')->nullable(),
                Toggle::make('is_featured')->label('Featured'),
                Toggle::make('is_trending')->label('Trending'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('image')->square()->disk('public'),
            TextColumn::make('name')->searchable()->sortable()->limit(30),
            TextColumn::make('brand.name')->sortable(),
            TextColumn::make('category.name')->sortable(),
            TextColumn::make('price')->money('NPR')->sortable(),
            IconColumn::make('is_featured')->boolean()->label('Featured'),
            IconColumn::make('is_trending')->boolean()->label('Trending'),
            TextColumn::make('views_count')->sortable()->label('Views'),
        ])->filters([
            SelectFilter::make('brand')->relationship('brand', 'name'),
            SelectFilter::make('category')->relationship('category', 'name'),
            TernaryFilter::make('is_featured'),
            TernaryFilter::make('is_trending'),
        ])->actions([EditAction::make()])
          ->bulkActions([DeleteBulkAction::make()])
          ->defaultSort('created_at', 'desc');
    }

    public static function getRelationManagers(): array
    {
        return [
            RelationManagers\ProductVariantsRelationManager::class, // SKU-based variant system
            RelationManagers\VariantsRelationManager::class,         // Legacy attribute options
            RelationManagers\ImagesRelationManager::class,
            RelationManagers\SpecsRelationManager::class,
            RelationManagers\PriceHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGadgets::route('/'),
            'create' => Pages\CreateGadget::route('/create'),
            'edit'   => Pages\EditGadget::route('/{record}/edit'),
        ];
    }
}
