<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Models\Slider;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-photo';
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Sliders';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->columnSpanFull()
                ->placeholder("Nepal's #1 Tech Price Tracker"),

            TextInput::make('subtitle')
                ->maxLength(255)
                ->placeholder('e.g. NEW ARRIVAL · LATEST TECH'),

            TextInput::make('badge')
                ->maxLength(100)
                ->placeholder('e.g. 🔥 Hot Deal'),

            Textarea::make('description')
                ->rows(2)
                ->maxLength(300)
                ->columnSpanFull()
                ->placeholder('Short description shown below the title…'),

            FileUpload::make('image')
                ->image()
                ->disk('public')
                ->directory('sliders')
                ->nullable()
                ->columnSpanFull()
                ->helperText('Optional background/feature image. Recommended: 1200×400px'),

            // Button 1
            TextInput::make('btn1_text')->label('Button 1 Text')->default('Browse Products'),
            TextInput::make('btn1_url')->label('Button 1 URL')->default('/gadgets'),
            Select::make('btn1_style')->label('Button 1 Style')
                ->options(['violet' => 'Violet', 'dark' => 'Dark', 'blue' => 'Blue', 'outline' => 'Outline'])
                ->default('violet'),

            // Button 2
            TextInput::make('btn2_text')->label('Button 2 Text')->nullable(),
            TextInput::make('btn2_url')->label('Button 2 URL')->nullable(),
            Select::make('btn2_style')->label('Button 2 Style')
                ->options(['violet' => 'Violet', 'dark' => 'Dark', 'blue' => 'Blue', 'outline' => 'Outline'])
                ->default('dark'),

            TextInput::make('sort_order')
                ->label('Sort Order')
                ->numeric()
                ->default(0)
                ->helperText('Lower number = shown first'),

            Toggle::make('is_active')->label('Active')->default(true),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('sort_order')->label('#')->sortable()->width('50px'),
            ImageColumn::make('image')->height(48)->disk('public')->defaultImageUrl(asset('images/placeholder.png')),
            TextColumn::make('title')->searchable()->limit(45)->weight('bold'),
            TextColumn::make('subtitle')->limit(35)->color('gray'),
            IconColumn::make('is_active')->boolean()->label('Active'),
            TextColumn::make('updated_at')->label('Updated')->dateTime('d M Y')->sortable(),
        ])
        ->reorderable('sort_order')
        ->defaultSort('sort_order')
        ->actions([
            EditAction::make()
                ->modalHeading('EDIT SLIDER')
                ->modalDescription('UPDATE HOMEPAGE HERO BANNER SLIDE DETAILS')
                ->modalSubmitActionLabel('SAVE CHANGES')
                ->modalWidth('lg'),
            \Filament\Actions\DeleteAction::make(),
        ])
        ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSliders::route('/'),
        ];
    }
}
