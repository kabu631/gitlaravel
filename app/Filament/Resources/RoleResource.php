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
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
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
                Section::make('Role Details')->columnSpanFull()
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

                Section::make('Menu & Module Permissions')->columnSpanFull()
                    ->description('Select which menus and modules users with this role can access, and which actions (view, create, update, delete) they may perform.')
                    ->schema([
                        static::modulePermissionsField(),
                    ]),
            ]);
    }

    public static function modulePermissionsField(bool $viaRelationship = true): Repeater
    {
        $field = Repeater::make('module_permissions')
            ->label('Granted System Modules')
            ->table([
                TableColumn::make('Menu / Module'),
                TableColumn::make('URL'),
                TableColumn::make('View')->alignCenter(),
                TableColumn::make('Create')->alignCenter(),
                TableColumn::make('Update')->alignCenter(),
                TableColumn::make('Delete')->alignCenter(),
            ])
            ->schema([
                Hidden::make('module_id'),
                Placeholder::make('module_name')
                    ->hiddenLabel()
                    ->content(fn (Get $get) => static::moduleLabel($get('module_id'))),
                Placeholder::make('module_url')
                    ->hiddenLabel()
                    ->content(fn (Get $get) => static::moduleUrl($get('module_id'))),
                Toggle::make('can_view')->hiddenLabel(),
                Toggle::make('can_create')->hiddenLabel(),
                Toggle::make('can_update')->hiddenLabel(),
                Toggle::make('can_delete')->hiddenLabel(),
            ])
            ->addable(false)
            ->deletable(false)
            ->reorderable(false)
            ->default(fn () => static::permissionRows())
            ->columnSpanFull();

        if (! $viaRelationship) {
            return $field;
        }

        return $field
            ->loadStateFromRelationshipsUsing(function (Repeater $component, ?Role $record) {
                $component->state(static::permissionRows($record));
            })
            ->saveRelationshipsUsing(function (Role $record, ?array $state) {
                $record->syncModulePermissions($state ?? []);
            })
            ->dehydrated(false);
    }

    protected static function activeModules()
    {
        return once(fn () => SystemModule::with('parent')
            ->where('status', 'active')
            ->orderBy('parent_id')
            ->orderBy('order')
            ->get()
            ->keyBy('id'));
    }

    protected static function moduleLabel(mixed $id): string
    {
        $mod = static::activeModules()->get($id);

        return $mod ? (($mod->parent?->name ?? 'Main Menu') . ' → ' . $mod->name) : '';
    }

    protected static function moduleUrl(mixed $id): string
    {
        return static::activeModules()->get($id)?->route ?: 'Menu group (no URL)';
    }

    public static function permissionRows(?Role $role = null): array
    {
        $granted = $role ? $role->systemModules()->get()->keyBy('id') : collect();

        return static::activeModules()->map(function (SystemModule $mod) use ($granted) {
            $pivot = $granted->get($mod->id)?->pivot;

            return [
                'module_id' => $mod->id,
                'can_view' => (bool) $pivot?->can_view,
                'can_create' => (bool) $pivot?->can_create,
                'can_update' => (bool) $pivot?->can_update,
                'can_delete' => (bool) $pivot?->can_delete,
            ];
        })->values()->all();
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
\Filament\Actions\ActionGroup::make([
                Action::make('menuPermissions')
                    ->label('Menu Permission')
                    ->icon('heroicon-o-key')
                    ->color('primary')
                    ->modalHeading(fn (Role $record) => "Menu Permissions — {$record->name}")
                    ->modalDescription('View and assign menu/module access with view, create, update, and delete actions.')
                    ->modalSubmitActionLabel('Save Permissions')
                    ->stickyModalHeader()
                    ->stickyModalFooter()
                    ->modalWidth('5xl')
                    ->fillForm(fn (Role $record) => ['module_permissions' => static::permissionRows($record)])
                    ->schema([static::modulePermissionsField(viaRelationship: false)])
                    ->action(function (Role $record, array $data) {
                        $record->syncModulePermissions($data['module_permissions'] ?? []);
                        Notification::make()->title('Menu permissions updated.')->success()->send();
                    }),

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
            'index' => ManageRoles::route('/'),
        ];
    }
}
