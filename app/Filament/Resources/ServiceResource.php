<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Services';
    protected static ?int $navigationSort = 6;

    public const GRADIENTS = [
        'linear-gradient(135deg, #6c5ce7, #4834d4)' => 'Violet',
        'linear-gradient(135deg, #1877F2, #0A66C2)' => 'Blue',
        'linear-gradient(135deg, #F56040, #C13584)' => 'Sunset',
        'linear-gradient(135deg, #00b894, #00cec9)' => 'Green',
        'linear-gradient(135deg, #ff9f43, #ff6b6b)' => 'Orange',
        'linear-gradient(135deg, #a29bfe, #6c5ce7)' => 'Lavender',
    ];

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Service')->columnSpanFull()->schema([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('icon')->required()->maxLength(8)->default('⭐')->helperText('An emoji'),
                Textarea::make('description')->required()->rows(3)->maxLength(500)->columnSpanFull(),
                Select::make('gradient')->options(self::GRADIENTS)->default(array_key_first(self::GRADIENTS))->required(),
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_active')->label('Active')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('sort_order')->label('#')->sortable(),
            TextColumn::make('icon'),
            TextColumn::make('title')->searchable()->weight('bold'),
            TextColumn::make('description')->limit(60)->color('gray'),
            IconColumn::make('is_active')->boolean()->label('Active'),
        ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->actions([
                ActionGroup::make([
                    EditAction::make()->modalHeading('EDIT SERVICE')->modalWidth('lg'),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageServices::route('/')];
    }
}
