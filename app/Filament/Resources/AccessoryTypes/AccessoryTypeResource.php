<?php

namespace App\Filament\Resources\AccessoryTypes;

use App\Filament\Resources\AccessoryTypes\Pages\ManageAccessoryTypes;
use App\Models\AccessoryType;
use BackedEnum;
use UnitEnum;
use Illuminate\Support\Str;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AccessoryTypeResource extends Resource
{
    protected static ?string $model = AccessoryType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;
    protected static UnitEnum|string|null $navigationGroup = 'Catalog';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Accessory Types';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Accessory Type Information')->columnSpanFull()->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug($state)))
                        ->placeholder('e.g. Wireless Charger'),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->maxLength(100)
                        ->nullable()
                        ->placeholder('e.g. wireless-charger')
                        ->helperText('Auto-generated from name. Leave blank to auto-fill.'),
                ])->columns(2)->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
\Filament\Actions\ActionGroup::make([
                EditAction::make()
                    ->modalHeading('Edit Accessory Type')
                    ->modalDescription('Update accessory type details')
                    ->modalSubmitActionLabel('Save changes')
                    ->modalWidth('md'),
                DeleteAction::make(),
            ]),
])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAccessoryTypes::route('/'),
        ];
    }
}
