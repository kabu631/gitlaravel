<?php

namespace App\Filament\Resources;

use App\Filament\Schemas\SeoForm;
use App\Filament\Resources\GadgetResource\Pages;
use App\Filament\Resources\GadgetResource\RelationManagers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\AccessoryType;
use App\Models\Gadget;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
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
            Section::make('Basic Info & Pricing')->columnSpanFull()->schema([
                TextInput::make('name')->required()->maxLength(255)->live(onBlur: true)
                    ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug($state))),
                SeoForm::slug('gadgets.show', required: true),
                Select::make('brand_id')->label('Brand')->relationship('brand', 'name')
                    ->searchable()->preload()->createOptionForm([
                        TextInput::make('name')->required(),
                    ])->required(),
                Select::make('category_id')->label('Category')->relationship('category', 'name')
                    ->searchable()->preload()->nullable(),
                TextInput::make('price')->numeric()->prefix('NPR')->required(),
                TextInput::make('old_price')->numeric()->prefix('NPR')->nullable()->label('Old Price'),
                Select::make('release_status')
                    ->label('Release status')
                    ->options(Gadget::STATUSES)
                    ->default(Gadget::STATUS_AVAILABLE)
                    ->required()
                    ->live()
                    ->helperText('Announced: launched globally, official Nepal price not out yet. Upcoming: not launched yet.'),
                TextInput::make('expected_launch')
                    ->label('Expected launch (text)')
                    ->placeholder('e.g. January–February 2027')
                    ->maxLength(120)
                    ->visible(fn ($get) => $get('release_status') !== Gadget::STATUS_AVAILABLE),
                Toggle::make('price_is_estimate')
                    ->label('Price is an estimate')
                    ->helperText('Shows "Expected price" on the site and leaves the price out of Product structured data.')
                    ->inline(false),
                TextInput::make('launch_price_note')
                    ->label('Launch price (when there is no Nepal price)')
                    ->placeholder('e.g. $999 US launch price (64GB)')
                    ->helperText('For discontinued models, grey-imported consoles and games. Set the Price to 0 and the site shows this instead.')
                    ->maxLength(120),
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
                    ->helperText('Select an existing type or click "+" to add a new one.')
                    ->columnSpanFull(),
            ])->columns(2),

            // ── Media ────────────────────────────────────────────────────────────
            Section::make('Media')->columnSpanFull()->schema([

                // Cover thumbnail (stored on gadgets table)
                FileUpload::make('image')
                    ->label('Cover / Thumbnail Image')
                    ->image()
                    ->disk('public')
                    ->directory('gadgets')
                    ->imagePreviewHeight('90')
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
                        TextInput::make('source_url')
                            ->label('Image source / credit URL')
                            ->url()
                            ->maxLength(500)
                            ->nullable(),
                        TextInput::make('credit')
                            ->label('Visible credit')
                            ->placeholder('e.g. Photo: Jane Doe, CC BY-SA 4.0, via Wikimedia Commons')
                            ->helperText('Shown under the image. Required for Creative Commons photos; leave empty for official images.')
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

            // ── Hardware Specifications (Powers Automated Algorithms) ─────────────
            Section::make('Hardware Specifications (Powers Automated Algorithms)')->columnSpanFull()
                ->description('Enter hardware specifications below. Our automated algorithms use these specs to highlight Marathon Battery, Pro Camera, High-FPS Gaming, and VFM rankings.')
                ->relationship('specs')
                ->schema([
                    TextInput::make('battery')
                        ->label('Battery Capacity & Charging')
                        ->placeholder('e.g. 5400mAh, 100W SUPERVOOC')
                        ->helperText('Values >= 5000mAh trigger "Marathon Battery" (>= 6000mAh triggers "Monster Battery").')
                        ->nullable(),
                    TextInput::make('camera')
                        ->label('Camera System')
                        ->placeholder('e.g. 50MP Sony IMX890 OIS + 64MP Periscope')
                        ->helperText('Values >= 50MP, OIS, Leica, Hasselblad trigger "Pro Camera".')
                        ->nullable(),
                    TextInput::make('processor')
                        ->label('Processor / SoC')
                        ->placeholder('e.g. Snapdragon 8 Gen 3 / RTX 4070')
                        ->helperText('Snapdragon 8-series, Dimensity 9-series, Apple Pro, RTX trigger "High-FPS Gaming".')
                        ->nullable(),
                    TextInput::make('display')
                        ->label('Display & Refresh Rate')
                        ->placeholder('e.g. 6.78" 120Hz LTPO AMOLED')
                        ->helperText('Displays with 120Hz, 144Hz, 165Hz+ trigger "High-FPS Gaming".')
                        ->nullable(),
                    TextInput::make('ram')
                        ->label('RAM')
                        ->placeholder('e.g. 16GB LPDDR5X')
                        ->nullable(),
                    TextInput::make('storage')
                        ->label('Internal Storage')
                        ->placeholder('e.g. 512GB UFS 4.0')
                        ->nullable(),
                    TextInput::make('os')
                        ->label('Operating System')
                        ->placeholder('e.g. Android 14, OxygenOS 14')
                        ->nullable(),
                    TextInput::make('connectivity')
                        ->label('Connectivity')
                        ->placeholder('e.g. 5G, Wi-Fi 7, Bluetooth 5.4')
                        ->nullable(),
                    TextInput::make('weight')
                        ->label('Weight')
                        ->placeholder('e.g. 220g')
                        ->nullable(),
                    TextInput::make('dimensions')
                        ->label('Dimensions')
                        ->placeholder('e.g. 164.3 x 75.8 x 9.15 mm')
                        ->nullable(),
                ])
                ->columns(2)
                ->collapsible(),

            Section::make('Details')->columnSpanFull()->schema([
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

            Section::make('FAQs')->columnSpanFull()
                ->description('Shown at the bottom of the product page and published as FAQPage structured data.')
                ->schema([
                    Repeater::make('faqs')
                        ->hiddenLabel()
                        ->schema([
                            TextInput::make('question')->required()->maxLength(255)->columnSpanFull(),
                            Textarea::make('answer')->required()->rows(3)->columnSpanFull(),
                        ])
                        ->addActionLabel('+ Add question')
                        ->reorderable()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                        ->defaultItems(0),
                ])->collapsible(),

            Section::make('Research sources')->columnSpanFull()
                ->description('Where prices and specs came from. Shown as a short "Sources" list on the product page.')
                ->schema([
                    Repeater::make('sources')
                        ->hiddenLabel()
                        ->schema([
                            TextInput::make('label')->required()->maxLength(160),
                            TextInput::make('url')->url()->required()->maxLength(500),
                            TextInput::make('accessed')->label('Checked on')->type('date'),
                        ])
                        ->columns(3)
                        ->addActionLabel('+ Add source')
                        ->defaultItems(0),
                ])->collapsible()->collapsed(),

            Section::make('Price Tracking Insights')->columnSpanFull()->schema([
                RichEditor::make('price_tracker_description')
                    ->nullable()
                    ->columnSpanFull()
                    ->helperText('Write analysis, price drop explanations, or insights here for the public Price Tracker page.'),
            ]),

            SeoForm::section(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('image')->size(36)->disk('public')->extraImgAttributes(['class' => 'rounded-md object-cover']),
            TextColumn::make('name')->searchable()->sortable()->limit(30),
            TextColumn::make('brand.name')->sortable(),
            TextColumn::make('category.name')->sortable(),
            TextColumn::make('price')->money('NPR')->sortable(),
            TextColumn::make('release_status')->label('Status')->badge()
                ->formatStateUsing(fn ($state) => Gadget::STATUSES[$state] ?? $state)
                ->color(fn ($state) => match ($state) {
                    Gadget::STATUS_UPCOMING  => 'warning',
                    Gadget::STATUS_ANNOUNCED => 'info',
                    default                  => 'success',
                }),
            TextColumn::make('algorithmic_badges')->sortable(false)
                ->label('Algo Highlights')
                ->badge()
                ->wrap()
                ->color(fn($state) => match(true) {
                    str_contains($state, 'Battery') => 'success',
                    str_contains($state, 'Camera')  => 'info',
                    str_contains($state, 'Performance') || str_contains($state, 'Gaming') => 'warning',
                    str_contains($state, 'VFM') => 'primary',
                    default => 'gray',
                })
                ->separator(' '),
            IconColumn::make('is_featured')->boolean()->label('Featured'),
            IconColumn::make('is_trending')->boolean()->label('Trending'),
            TextColumn::make('views_count')->sortable()->label('Views'),
        ])->filters([
            SelectFilter::make('brand')->relationship('brand', 'name'),
            SelectFilter::make('category')->relationship('category', 'name'),
            SelectFilter::make('release_status')->label('Status')->options(Gadget::STATUSES),
            // Quickly find products that still need a picture.
            TernaryFilter::make('has_image')
                ->label('Has image')
                ->placeholder('All products')
                ->trueLabel('With image')
                ->falseLabel('Missing image')
                ->queries(
                    true: fn ($q) => $q->whereNotNull('image')->where('image', '!=', ''),
                    false: fn ($q) => $q->where(fn ($q) => $q->whereNull('image')->orWhere('image', '')),
                ),
            \Filament\Tables\Filters\Filter::make('marathon_battery')
                ->label('🔋 Marathon Battery (≥5000mAh)')
                ->query(fn($q) => $q->whereHas('specs', fn($s) => $s->where('battery', 'like', '%5000%')
                    ->orWhere('battery', 'like', '%5400%')
                    ->orWhere('battery', 'like', '%5500%')
                    ->orWhere('battery', 'like', '%6000%')
                    ->orWhere('battery', 'like', '%7000%')
                )),
            \Filament\Tables\Filters\Filter::make('pro_camera')
                ->label('📸 Pro Camera (≥50MP / OIS)')
                ->query(fn($q) => $q->whereHas('specs', fn($s) => $s->where('camera', 'like', '%50MP%')
                    ->orWhere('camera', 'like', '%64MP%')
                    ->orWhere('camera', 'like', '%108MP%')
                    ->orWhere('camera', 'like', '%200MP%')
                    ->orWhere('camera', 'like', '%Leica%')
                    ->orWhere('camera', 'like', '%Hasselblad%')
                )),
            \Filament\Tables\Filters\Filter::make('high_fps_gaming')
                ->label('⚡ High-FPS Gaming (120Hz+ / Flagship)')
                ->query(fn($q) => $q->whereHas('specs', fn($s) => $s->where('display', 'like', '%120Hz%')
                    ->orWhere('display', 'like', '%144Hz%')
                    ->orWhere('display', 'like', '%165Hz%')
                    ->orWhere('processor', 'like', '%Snapdragon 8%')
                    ->orWhere('processor', 'like', '%Dimensity 9%')
                    ->orWhere('processor', 'like', '%RTX%')
                )),
            TernaryFilter::make('is_featured'),
            TernaryFilter::make('is_trending'),
        ])->actions([
\Filament\Actions\ActionGroup::make([
            EditAction::make(),
            Action::make('view')
                ->label('View')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn($record) => route('gadgets.show', $record->slug))
                ->openUrlInNewTab(),
\Filament\Actions\DeleteAction::make(),
]),
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
