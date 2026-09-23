<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarrierFrequencyBandResource\Pages;
use App\Models\CarrierFrequencyBand;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class CarrierFrequencyBandResource extends Resource
{
    protected static ?string $model = CarrierFrequencyBand::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-signal';
    protected static UnitEnum|string|null $navigationGroup = 'Tech Lab & Tools';
    protected static ?string $navigationLabel = '5G & 4G Frequency Bands';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Mobile Carrier Frequency Band')->schema([
                Select::make('carrier')
                    ->label('Mobile Carrier Operator')
                    ->options([
                        'ntc'   => 'Nepal Telecom (NTC)',
                        'ncell' => 'Ncell Axiata',
                    ])
                    ->required(),

                TextInput::make('code')
                    ->label('Band Code')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('e.g. Band n78 (3500MHz)'),

                TextInput::make('technology')
                    ->label('Network Technology')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('e.g. 5G Sub-6 / 4G LTE FDD'),

                TextInput::make('frequency')
                    ->label('Frequency Band')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('e.g. 3500 MHz (C-Band) / 1800 MHz (Band 3)'),

                Select::make('status_badge')
                    ->label('Deployment Status Badge')
                    ->options([
                        'Trial Live'         => 'Trial Live (5G)',
                        'Commercial Primary' => 'Commercial Primary',
                        'Commercial'         => 'Commercial',
                        'Planned / Testing'  => 'Planned / Testing',
                    ])
                    ->default('Commercial')
                    ->required(),

                Textarea::make('role')
                    ->label('Coverage & Performance Role')
                    ->rows(2)
                    ->maxLength(300)
                    ->columnSpanFull()
                    ->placeholder('e.g. High-density urban speed layer. Tested in Sundhara and Babarmaal.'),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower number = shown first'),

                Toggle::make('is_active')
                    ->label('Active on Radar')
                    ->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('sort_order')->label('#')->sortable()->width('50px'),
            TextColumn::make('carrier')
                ->label('Carrier')
                ->badge()
                ->formatStateUsing(fn($state) => strtoupper($state))
                ->color(fn($state) => $state === 'ntc' ? 'gray' : 'primary'),
            TextColumn::make('code')->label('Band Code')->weight('bold')->searchable(),
            TextColumn::make('technology')->label('Tech'),
            TextColumn::make('frequency')->label('Frequency'),
            TextColumn::make('status_badge')->label('Status')->badge()->color('success'),
            IconColumn::make('is_active')->boolean()->label('Active'),
        ])
        ->filters([
            SelectFilter::make('carrier')
                ->options([
                    'ntc'   => 'Nepal Telecom (NTC)',
                    'ncell' => 'Ncell Axiata',
                ]),
        ])
        ->reorderable('sort_order')
        ->defaultSort('sort_order')
        ->actions([
            EditAction::make()
                ->modalHeading('EDIT FREQUENCY BAND')
                ->modalDescription('UPDATE 5G OR 4G CARRIER FREQUENCY BAND DETAILS')
                ->modalSubmitActionLabel('SAVE CHANGES')
                ->modalWidth('lg'),
            \Filament\Actions\DeleteAction::make(),
        ])
        ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCarrierFrequencyBands::route('/'),
        ];
    }
}
