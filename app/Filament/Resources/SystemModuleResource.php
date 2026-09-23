<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SystemModuleResource\Pages\ManageSystemModules;
use App\Models\SystemModule;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use BackedEnum;
use UnitEnum;

class SystemModuleResource extends Resource
{
    protected static ?string $model = SystemModule::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-squares-plus';
    protected static UnitEnum|string|null $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'System Modules';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Menu Title')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('e.g. Smart Watches & Wearables')
                    ->helperText('The name shown to users in the navigation menu.')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if (blank($get('code')) && filled($state)) {
                            $set('code', Str::snake(Str::lower($state)));
                        }
                    }),

                TextInput::make('route')
                    ->label('Menu Link (Route)')
                    ->maxLength(255)
                    ->placeholder('e.g. /secure-admin/gadgets')
                    ->helperText('The page this menu item opens. Leave empty for a menu that groups sub-items.'),

                TextInput::make('code')
                    ->label('Module Code')
                    ->required()
                    ->maxLength(100)
                    ->unique(ignoreRecord: true)
                    ->regex('/^[a-z0-9_]+$/')
                    ->validationMessages([
                        'regex' => 'Lowercase letters, numbers and underscores only.',
                    ])
                    ->placeholder('e.g. smart_watches')
                    ->helperText('Unique identifier used for permissions. Lowercase letters, numbers and underscores only.'),

                TextInput::make('order')
                    ->label('Menu Order')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->placeholder('1')
                    ->helperText('Position in the menu — a lower number appears higher up.'),

                Select::make('icon')
                    ->label('Menu Icon')
                    ->options(SystemModule::getIconOptions())
                    ->searchable()
                    ->preload()
                    ->placeholder('Select an icon')
                    ->helperText('Icon displayed beside the menu title.')
                    ->nullable(),

                Select::make('status')
                    ->label('Module Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
                    ->required()
                    ->helperText('Inactive modules stay saved but are hidden from the menu.'),

                Select::make('parent_id')
                    ->label('Parent Menu')
                    ->placeholder('None — show as a top-level menu')
                    ->options(fn ($record) => SystemModule::query()
                        ->whereNull('parent_id')
                        ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                        ->orderBy('order')
                        ->pluck('name', 'id')
                    )
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('sub_parent_id', null))
                    ->mutateDehydratedStateUsing(fn ($state) => blank($state) ? null : (int) $state)
                    ->helperText('The main menu this item belongs under.')
                    ->nullable(),

                Select::make('sub_parent_id')
                    ->label('Parent Sub-menu')
                    ->placeholder(fn (callable $get) => filled($get('parent_id'))
                        ? 'None — place directly under the parent menu'
                        : 'Select a parent menu first.'
                    )
                    ->options(fn (callable $get, $record) => filled($get('parent_id'))
                        ? SystemModule::query()
                            ->where('parent_id', $get('parent_id'))
                            ->whereNull('sub_parent_id')
                            ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                            ->orderBy('order')
                            ->pluck('name', 'id')
                        : []
                    )
                    ->disabled(fn (callable $get) => blank($get('parent_id')))
                    ->mutateDehydratedStateUsing(fn ($state) => blank($state) ? null : (int) $state)
                    ->helperText('Select a parent menu first.')
                    ->nullable(),

                Checkbox::make('show_in_menu')
                    ->label('Show in Navigation Menu')
                    ->default(true)
                    ->columnSpanFull()
                    ->helperText('Turn off to keep the module active for permissions but hidden from the navigation menu.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('order')
            ->columns([
                TextColumn::make('order')
                    ->label('Order')
                    ->sortable()
                    ->width('60px')
                    ->alignCenter(),

                TextColumn::make('name')
                    ->label('Menu Title')
                    ->searchable()
                    ->sortable()
                    ->icon(fn ($record) => $record->icon ?: 'heroicon-o-minus')
                    ->weight('bold'),

                TextColumn::make('code')
                    ->label('Module Code')
                    ->searchable()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('route')
                    ->label('Menu Link')
                    ->searchable()
                    ->placeholder('None (Group Header)')
                    ->limit(28),

                TextColumn::make('parent.name')
                    ->label('Parent Menu')
                    ->placeholder('Top Level')
                    ->badge()
                    ->color('info'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                IconColumn::make('show_in_menu')
                    ->label('In Menu')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
                TernaryFilter::make('show_in_menu')
                    ->label('In Navigation Menu'),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit System Module')
                    ->modalDescription('Update menu item and choose where it appears in the navigation.')
                    ->modalSubmitActionLabel('Save menu module')
                    ->stickyModalHeader()
                    ->stickyModalFooter()
                    ->modalWidth('2xl'),
                DeleteAction::make()
                    ->modalHeading('Delete System Module')
                    ->modalDescription('Are you sure you want to delete this module? It will be removed from navigation.')
                    ->modalSubmitActionLabel('Delete'),
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
            'index' => ManageSystemModules::route('/'),
        ];
    }
}
