<?php

namespace App\Filament\Resources\UpcomingLaunches;

use App\Models\UpcomingLaunch;
use App\Filament\Resources\UpcomingLaunches\Pages\ListUpcomingLaunches;
use App\Filament\Resources\UpcomingLaunches\Pages\CreateUpcomingLaunch;
use App\Filament\Resources\UpcomingLaunches\Pages\EditUpcomingLaunch;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class UpcomingLaunchResource extends Resource
{
    protected static ?string $model = UpcomingLaunch::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rocket-launch';
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Upcoming Launches';
    protected static ?int $navigationSort = 4;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Upcoming Launch Information')->columnSpanFull()->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. iPhone 17 Pro Max')
                    ->columnSpanFull(),

                TextInput::make('brand')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('e.g. Apple'),

                TextInput::make('category')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('e.g. Smartphones, Laptops, Audio'),

                TextInput::make('expected_date')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('e.g. Expected Oct 2025')
                    ->helperText('Free-form text shown on the homepage tile'),

                TextInput::make('est_price')
                    ->required()
                    ->maxLength(100)
                    ->label('Estimated Price')
                    ->placeholder('e.g. Rs. 2,19,999'),

                TextInput::make('confidence')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(90)
                    ->suffix('%')
                    ->helperText('How confident are we (0–100)?'),

                TextInput::make('badge')
                    ->maxLength(100)
                    ->nullable()
                    ->placeholder('e.g. High Anticipation, Confirmed Specs'),

                Select::make('tag_color')
                    ->options([
                        'emerald' => 'Emerald (Green)',
                        'blue'    => 'Blue',
                        'purple'  => 'Purple',
                        'amber'   => 'Amber (Gold)',
                        'rose'    => 'Rose (Pink)',
                    ])
                    ->default('emerald')
                    ->required(),

                Textarea::make('highlight')
                    ->rows(2)
                    ->nullable()
                    ->columnSpanFull()
                    ->placeholder('Key feature highlights, e.g. A19 Pro TSMC 2nm, 24MP CenterStage Front Cam'),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower = shown first on homepage'),

                Toggle::make('is_active')
                    ->label('Show on Homepage')
                    ->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('sort_order')->label('#')->sortable()->width('50px'),
            TextColumn::make('name')->searchable()->limit(35)->weight('bold'),
            TextColumn::make('brand')->badge()->color('gray'),
            TextColumn::make('category')->badge()->color('primary'),
            TextColumn::make('expected_date')->label('Expected')->limit(25),
            TextColumn::make('est_price')->label('Est. Price'),
            TextColumn::make('confidence')->label('Confidence')->suffix('%')
                ->badge()
                ->color(fn($state) => $state >= 90 ? 'success' : ($state >= 70 ? 'warning' : 'danger')),
            IconColumn::make('is_active')->boolean()->label('Active'),
        ])->reorderable('sort_order')
          ->defaultSort('sort_order')
          ->filters([
              TernaryFilter::make('is_active')->label('Active'),
          ])
          ->actions([
\Filament\Actions\ActionGroup::make([
              EditAction::make()
                  ->modalHeading('EDIT UPCOMING LAUNCH')
                  ->modalDescription('UPDATE UPCOMING GADGET LAUNCH DETAILS')
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
            'index' => Pages\ManageUpcomingLaunches::route('/'),
        ];
    }
}
