<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorizedServiceCenterResource\Pages;
use App\Models\AuthorizedServiceCenter;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
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

class AuthorizedServiceCenterResource extends Resource
{
    protected static ?string $model = AuthorizedServiceCenter::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static UnitEnum|string|null $navigationGroup = 'Tech Lab & Tools';
    protected static ?string $navigationLabel = 'Repair & Service Centers';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Authorized Repair & Service Center')->columnSpanFull()->schema([
                Select::make('brand')
                    ->label('Hardware Brand')
                    ->options([
                        'Apple'   => 'Apple',
                        'Samsung' => 'Samsung',
                        'Xiaomi'  => 'Xiaomi / Redmi / POCO',
                        'OnePlus' => 'OnePlus',
                        'Realme'  => 'Realme',
                        'Vivo'    => 'Vivo',
                        'Dell'    => 'Dell',
                        'Lenovo'  => 'Lenovo',
                        'HP'      => 'HP',
                        'ASUS'    => 'ASUS / ROG',
                        'Sony'    => 'Sony',
                    ])
                    ->searchable()
                    ->required(),

                TextInput::make('name')
                    ->label('Service Center Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. Generation Next (Genxt) Apple Care'),

                TextInput::make('address')
                    ->label('Physical Street Address')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. Sherpa Mall, Durbar Marg, Kathmandu'),

                TextInput::make('city')
                    ->label('City / District')
                    ->default('Kathmandu')
                    ->required()
                    ->maxLength(100),

                TextInput::make('phone')
                    ->label('Helpline / Direct Phone')
                    ->tel()
                    ->placeholder('e.g. +977 1-4228945'),

                TextInput::make('email')
                    ->label('Official Support Email')
                    ->email()
                    ->placeholder('e.g. support@servicecenter.com.np'),

                TextInput::make('avg_screen_cost')
                    ->label('Average Screen Replacement Cost (Estimate)')
                    ->maxLength(100)
                    ->placeholder('e.g. Rs. 28,000 - 42,000 (OLED)'),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower number = shown first'),

                Toggle::make('is_authorized')
                    ->label('Official Manufacturer Authorized')
                    ->default(true),

                Toggle::make('is_active')
                    ->label('Active in Directory')
                    ->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('sort_order')->label('#')->sortable()->width('50px'),
            TextColumn::make('brand')->label('Brand')->badge()->color('primary')->sortable(),
            TextColumn::make('name')->label('Service Center')->weight('bold')->searchable(),
            TextColumn::make('address')->label('Location')->limit(30)->searchable(),
            TextColumn::make('phone')->label('Phone'),
            TextColumn::make('avg_screen_cost')->label('Avg Screen Fix')->placeholder('—'),
            IconColumn::make('is_authorized')->boolean()->label('Official'),
            IconColumn::make('is_active')->boolean()->label('Active'),
        ])
        ->filters([
            SelectFilter::make('brand')
                ->options([
                    'Apple'   => 'Apple',
                    'Samsung' => 'Samsung',
                    'Xiaomi'  => 'Xiaomi',
                    'OnePlus' => 'OnePlus',
                    'Dell'    => 'Dell',
                    'Lenovo'  => 'Lenovo',
                ]),
        ])
        ->reorderable('sort_order')
        ->defaultSort('sort_order')
        ->actions([
\Filament\Actions\ActionGroup::make([
            EditAction::make()
                ->modalHeading('EDIT SERVICE CENTER')
                ->modalDescription('UPDATE AUTHORIZED REPAIR & SERVICE CENTER DETAILS')
                ->modalSubmitActionLabel('SAVE CHANGES')
                ->modalWidth('lg'),
            \Filament\Actions\DeleteAction::make(),
        ]),
])
        ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAuthorizedServiceCenters::route('/'),
        ];
    }
}
