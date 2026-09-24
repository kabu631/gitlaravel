<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
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
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-bars-3';
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Header Menu';
    protected static ?string $modelLabel = 'Menu Item';
    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Menu Item')->columnSpanFull()->schema([
                Select::make('parent_id')->label('Parent (leave empty for top-level)')
                    ->options(fn () => MenuItem::whereNull('parent_id')->orderBy('sort_order')->pluck('label', 'id'))
                    ->searchable()->nullable()->columnSpanFull()
                    ->helperText('Top-level items with children become dropdowns.'),
                Select::make('type')->options(MenuItem::TYPES)->default('link')->required()->live(),
                TextInput::make('label')->required()->maxLength(80),
                TextInput::make('url')->label('Link / URL')->maxLength(500)
                    ->placeholder('/products?category=mobile or https://…')
                    ->helperText('Leave empty for a dropdown parent.')
                    ->visible(fn ($get) => in_array($get('type'), ['link', null], true))
                    ->columnSpanFull(),
                Select::make('icon')->options(MenuItem::ICONS)->searchable()->nullable(),
                Select::make('style')->options(MenuItem::STYLES)->default('default')->required(),
                TextInput::make('subtitle')->maxLength(120)->helperText('Small text under the label (dropdown items).'),
                TextInput::make('badge_text')->maxLength(20)->placeholder('NEW / HOT / AI'),
                TextInput::make('sort_order')->numeric()->default(0)->helperText('Lower shows first'),
                Toggle::make('open_in_new_tab')->label('Open in new tab'),
                Toggle::make('show_on_mobile')->label('Show in mobile menu')->default(true),
                Toggle::make('is_active')->label('Visible')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('label')->searchable()->weight('bold'),
            TextColumn::make('parent.label')->label('Parent')->placeholder('— Top level —'),
            TextColumn::make('type')->badge()->formatStateUsing(fn ($s) => MenuItem::TYPES[$s] ?? $s),
            TextColumn::make('url')->limit(40)->placeholder('—'),
            TextColumn::make('sort_order')->label('Order')->sortable(),
            IconColumn::make('show_on_mobile')->boolean()->label('Mobile'),
            ToggleColumn::make('is_active')->label('Visible'),
        ])
            ->modifyQueryUsing(fn ($q) => $q->with('parent')->orderByRaw('COALESCE(parent_id, id), parent_id IS NOT NULL'))
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('parent_id')->label('Parent')
                    ->options(fn () => MenuItem::whereNull('parent_id')->pluck('label', 'id')),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()->modalHeading('EDIT MENU ITEM')->modalWidth('lg'),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageMenuItems::route('/')];
    }
}
