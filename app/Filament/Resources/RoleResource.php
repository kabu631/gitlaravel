<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages\ManageRoles;
use App\Models\Role;
use App\Models\SystemModule;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-shield-check';
    protected static UnitEnum|string|null $navigationGroup = 'Users';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Roles & Permissions';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Role Details')
                    ->description('Set the display name, unique identifier, and description for this role.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Role Name')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g. Catalog Manager')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if (blank($get('slug')) && filled($state)) {
                                    $set('slug', Str::snake(Str::lower($state)));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Role Identifier (Slug)')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->regex('/^[a-z0-9_]+$/')
                            ->validationMessages([
                                'regex' => 'Lowercase letters, numbers, and underscores only.',
                            ])
                            ->placeholder('e.g. catalog_manager')
                            ->helperText('Unique programmatic identifier for permissions.')
                            ->disabled(fn ($record) => (bool) ($record?->is_system)),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(2)
                            ->placeholder('Describe the responsibilities and permissions granted to this role...')
                            ->columnSpanFull()
                            ->nullable(),
                    ])
                    ->columns(2),

                Section::make('Menu & Module Permissions')
                    ->description('Select which navigation menus, dashboard pages, and modules users with this role can see and access.')
                    ->schema([
                        CheckboxList::make('systemModules')
                            ->label('Granted System Modules')
                            ->relationship('systemModules', 'name')
                            ->options(function () {
                                $modules = SystemModule::with('parent')
                                    ->where('status', 'active')
                                    ->orderBy('parent_id')
                                    ->orderBy('order')
                                    ->get();

                                $options = [];
                                foreach ($modules as $mod) {
                                    $groupName = $mod->parent ? $mod->parent->name : 'Main Menu';
                                    $options[$mod->id] = "{$groupName} → {$mod->name}";
                                }

                                return $options;
                            })
                            ->descriptions(function () {
                                $modules = SystemModule::where('status', 'active')->get();
                                $descriptions = [];
                                foreach ($modules as $mod) {
                                    $route = $mod->route ?: 'Menu Group (Header)';
                                    $descriptions[$mod->id] = "Code: {$mod->code} · {$route}";
                                }

                                return $descriptions;
                            })
                            ->bulkToggleable()
                            ->searchable()
                            ->columns(['default' => 1, 'sm' => 2])
                            ->gridDirection('row')
                            ->helperText('Use Select All / Deselect All or search to quickly manage permissions.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Role')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon(fn (Role $record) => $record->is_system ? 'heroicon-o-shield-check' : 'heroicon-o-user-group')
                    ->iconColor(fn (Role $record) => $record->is_system ? 'primary' : 'gray')
                    ->description(fn (Role $record) => $record->is_system ? 'System Role (Protected)' : null),

                TextColumn::make('slug')
                    ->label('Code')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(65)
                    ->color('gray')
                    ->placeholder('No description provided'),

                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Assigned Users')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                TextColumn::make('system_modules_count')
                    ->counts('systemModules')
                    ->label('Permitted Menus')
                    ->badge()
                    ->color('primary')
                    ->alignCenter(),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit Role & Permissions')
                    ->modalDescription('Update role details and granted menu module permissions.')
                    ->modalSubmitActionLabel('Save Changes')
                    ->stickyModalHeader()
                    ->stickyModalFooter()
                    ->modalWidth('4xl'),

                DeleteAction::make()
                    ->modalHeading('Delete Role')
                    ->modalDescription('Are you sure you want to delete this role? Assigned users will lose this role.')
                    ->visible(fn (Role $record) => ! $record->is_system),
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
            'index' => ManageRoles::route('/'),
        ];
    }
}
