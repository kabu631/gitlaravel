<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RivalMatchupResource\Pages;
use App\Models\Category;
use App\Models\Gadget;
use App\Models\RivalMatchup;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class RivalMatchupResource extends Resource
{
    protected static ?string $model = RivalMatchup::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-scale';
    protected static UnitEnum|string|null $navigationGroup = 'Tech Lab & Tools';
    protected static ?string $navigationLabel = 'Rival Matchups Arena';
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Matchup Overview')->columnSpanFull()->schema([
                TextInput::make('title')
                    ->label('Matchup Title')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. Ultra Flagship Smartphone Showdown'),

                TextInput::make('subtitle')
                    ->label('Subtitle / Context')
                    ->maxLength(255)
                    ->placeholder('e.g. Latest hot flagships compared head-to-head on Nepal pricing & benchmarks'),

                Select::make('category_slug')
                    ->label('Product Category')
                    ->options([
                        'mobile'     => 'Smartphones (Mobile)',
                        'laptop'     => 'Laptops',
                        'earbuds'    => 'Audio & Earbuds',
                        'smartwatch' => 'Smartwatches',
                        'tablet'     => 'Tablets',
                    ])
                    ->required(),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower number = shown first'),

                Toggle::make('is_active')
                    ->label('Active on Home Page Showdown')
                    ->default(true),
            ])->columns(2),

            Section::make('Rival Flagship Devices')->columnSpanFull()->schema([
                Select::make('device_a_id')
                    ->label('Device Alpha (Left)')
                    ->options(fn() => Gadget::orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('device_b_id')
                    ->label('Device Beta (Right)')
                    ->options(fn() => Gadget::orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
            ])->columns(2),

            Section::make('Head-to-Head Comparison Metrics')->columnSpanFull()->schema([
                Repeater::make('metrics')
                    ->label('Duel Breakdown Metrics')
                    ->schema([
                        TextInput::make('label')
                            ->label('Metric Name')
                            ->placeholder('e.g. Performance & NPU')
                            ->required()
                            ->columnSpan(2),

                        TextInput::make('left_val')
                            ->label('Device A Spec/Score')
                            ->placeholder('e.g. 5000mAh · 45W')
                            ->required(),

                        TextInput::make('right_val')
                            ->label('Device B Spec/Score')
                            ->placeholder('e.g. 4422mAh · 27W')
                            ->required(),

                        TextInput::make('left_pct')
                            ->label('Device A Score %')
                            ->numeric()
                            ->default(50)
                            ->prefix('%')
                            ->helperText('Relative visual bar split (0-100)'),

                        TextInput::make('right_pct')
                            ->label('Device B Score %')
                            ->numeric()
                            ->default(50)
                            ->prefix('%'),
                    ])
                    ->columns(6)
                    ->collapsible()
                    ->addActionLabel('+ Add Comparison Metric')
                    ->columnSpanFull()
                    ->defaultItems(4),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('sort_order')->label('#')->sortable()->width('50px'),
            TextColumn::make('title')->label('Showdown Title')->weight('bold')->searchable(),
            TextColumn::make('category_slug')->label('Category')->badge()->color('warning'),
            TextColumn::make('deviceA.name')->label('Device A')->limit(25),
            TextColumn::make('deviceB.name')->label('Device B')->limit(25),
            IconColumn::make('is_active')->boolean()->label('Active'),
            TextColumn::make('updated_at')->label('Updated')->dateTime('d M Y')->sortable(),
        ])
        ->reorderable('sort_order')
        ->defaultSort('sort_order')
        ->actions([
\Filament\Actions\ActionGroup::make([EditAction::make(),
\Filament\Actions\DeleteAction::make(),
]),
])
        ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRivalMatchups::route('/'),
            'create' => Pages\CreateRivalMatchup::route('/create'),
            'edit'   => Pages\EditRivalMatchup::route('/{record}/edit'),
        ];
    }
}
