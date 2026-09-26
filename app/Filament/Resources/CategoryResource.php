<?php

namespace App\Filament\Resources;

use App\Filament\Schemas\SeoForm;
use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use BackedEnum;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-tag';
    protected static UnitEnum|string|null $navigationGroup = 'Catalog';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Category Information')->columnSpanFull()->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(50)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug($state)))
                    ->placeholder('e.g. Smartphone'),

                SeoForm::slug(required: true)
                    ->maxLength(50)
                    ->placeholder('e.g. smartphone'),
            ])->columns(1),

            SeoForm::section(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('slug'),
            TextColumn::make('gadgets_count')->counts('gadgets')->label('Products'),
        ])->actions([
\Filament\Actions\ActionGroup::make([
            EditAction::make()
                ->modalHeading('EDIT CATEGORY')
                ->modalDescription('UPDATE CATEGORY DETAILS')
                ->modalSubmitActionLabel('SAVE CHANGES')
                ->modalWidth('3xl'),
            DeleteAction::make(),
        ]),
])
          ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}
