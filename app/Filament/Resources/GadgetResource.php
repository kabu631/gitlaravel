<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GadgetResource\Pages;
use App\Filament\Resources\GadgetResource\RelationManagers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\AccessoryType;
use App\Models\Gadget;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Actions\Action;
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
                Select::make('accessory_type')
                    ->label('Accessory Type')
                    ->options(fn() => AccessoryType::orderBy('name')->pluck('name', 'slug')->toArray())
                    ->searchable()
                    ->nullable()
                    ->createOptionForm([
                        TextInput::make('name')->required()->maxLength(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                        TextInput::make('slug')->required()->maxLength(100),
                    ])
                    ->createOptionUsing(function (array $data) {
                        $type = AccessoryType::create($data);
                        return $type->slug;
                    })
                    ->helperText('Select an existing type or click "+" to add a new one.'),
            ])->columns(2),

            Section::make('Pricing')->schema([
                TextInput::make('price')->numeric()->prefix('NPR')->required(),
                TextInput::make('old_price')->numeric()->prefix('NPR')->nullable()->label('Old Price'),
            ])->columns(2),

            // ── Media ────────────────────────────────────────────────────────────
            Section::make('Media')->schema([

                // Cover thumbnail (stored on gadgets table)
                FileUpload::make('image')
                    ->label('Cover / Thumbnail Image')
                    ->image()
                    ->disk('public')
                    ->directory('gadgets')
                    ->imagePreviewHeight('150')
                    ->nullable()
                    ->helperText('Primary thumbnail shown in product listings.'),

                // ── Product Gallery (stored in gadget_images table) ──────────────
                Repeater::make('images')
                    ->label('Product Gallery Images')
                    ->relationship('images')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Image')
                            ->image()
                            ->disk('public')
                            ->directory('gadgets/gallery')
                            ->imagePreviewHeight('120')
                            ->required()
                            ->columnSpan(2),
                        TextInput::make('alt_text')
                            ->label('Alt Text')
                            ->placeholder('e.g. Front view of Notebook Pro')
                            ->maxLength(255)
                            ->nullable(),
                        TextInput::make('order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(4)
                    ->addActionLabel('+ Add Image')
                    ->reorderable('order')
                    ->collapsible()
                    ->defaultItems(0)
                    ->helperText('Add multiple product images. Drag to reorder. Each image appears in the product gallery.')
                    ->columnSpanFull(),

                // 3D / embed
                FileUpload::make('model_3d')->disk('public')->directory('gadgets/3d')->nullable()->label('3D Model (.glb)'),
                TextInput::make('sketchfab_embed')->nullable()->label('Sketchfab Embed Code'),
            ]),

            Section::make('Details')->schema([
                RichEditor::make('description')->nullable()->columnSpanFull(),
                TextInput::make('release_date')->type('date')->nullable(),
                TextInput::make('buy_url')
                    ->label('Buy URL')
                    ->url()
                    ->nullable()
                    ->placeholder('https://onin.com.np/product/...')
                    ->helperText('Direct purchase link — used for "Buy Now" button on product page'),
                Toggle::make('is_featured')->label('Featured'),
                Toggle::make('is_trending')->label('Trending'),
            ])->columns(2),

            Section::make('Price Tracking Insights')->schema([
                RichEditor::make('price_tracker_description')
                    ->nullable()
                    ->columnSpanFull()
                    ->helperText('Write analysis, price drop explanations, or insights here for the public Price Tracker page.'),
            ]),
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
        ])->actions([
            EditAction::make(),
            Action::make('view')
                ->label('View')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn($record) => route('gadgets.show', $record->slug))
                ->openUrlInNewTab(),
        ])
          ->bulkActions([DeleteBulkAction::make()])
          ->defaultSort('created_at', 'desc');
    }

    public static function getRelationManagers(): array
    {
        return [
            RelationManagers\ProductVariantsRelationManager::class,
            RelationManagers\VariantsRelationManager::class,
            RelationManagers\SpecsRelationManager::class,
            RelationManagers\PriceHistoryRelationManager::class,
            // ImagesRelationManager removed — gallery is now inline in the form above
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
