<?php

namespace App\Filament\Resources;

use App\Filament\Schemas\SeoForm;
use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use BackedEnum;
use UnitEnum;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office';
    protected static UnitEnum|string|null $navigationGroup = 'Catalog';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Brand Information')->columnSpanFull()->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(100)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug($state)))
                    ->placeholder('e.g. Samsung'),

                SeoForm::slug('brands.show', required: true)
                    ->maxLength(100)
                    ->placeholder('e.g. samsung'),

                FileUpload::make('logo')
                    ->label('Brand Logo')
                    ->image()
                    ->disk('public')
                    ->directory('brands')
                    ->imagePreviewHeight('80')
                    ->nullable(),

                Select::make('categories')
                    ->label('Categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->placeholder('Select categories this brand belongs to'),
            ])->columns(1),

            SeoForm::section(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('logo')->circular()->disk('public'),
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('slug'),
            TextColumn::make('categories.name')->sortable(false)
                ->label('Categories')
                ->badge()
                ->color('primary')
                ->separator(', '),
            TextColumn::make('gadgets_count')->counts('gadgets')->label('Products'),
        ])->actions([
\Filament\Actions\ActionGroup::make([
            EditAction::make()
                ->modalHeading('Edit Brand')
                ->modalDescription('Update brand details')
                ->modalSubmitActionLabel('Save changes')
                ->modalWidth('3xl'),
            DeleteAction::make(),
        ]),
])
          ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBrands::route('/'),
        ];
    }
}
