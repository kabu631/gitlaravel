<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PopupResource\Pages;
use App\Models\Popup;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PopupResource extends Resource
{
    protected static ?string $model = Popup::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-megaphone';
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Site Popups';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Popup Content')->columnSpanFull()->schema([
                TextInput::make('title')->required()->maxLength(255)->columnSpanFull(),
                TextInput::make('badge')->maxLength(60)->placeholder('e.g. 🎉 Limited Offer')->columnSpanFull(),
                Textarea::make('body')->rows(3)->maxLength(600)->columnSpanFull()
                    ->placeholder('Short message shown under the title'),
                FileUpload::make('image')->image()->disk('public')->directory('popups')->nullable()->columnSpanFull()
                    ->helperText('Shown at the top of the popup. Recommended: 800×450px'),
                TextInput::make('btn_text')->label('Button Text')->maxLength(60),
                TextInput::make('btn_url')->label('Button URL')->maxLength(500)
                    ->placeholder('/products or https://…'),
            ])->columns(2),

            Section::make('Behaviour & Schedule')->columnSpanFull()->schema([
                Select::make('frequency')->options(Popup::FREQUENCIES)->default('once_per_session')->required(),
                TextInput::make('delay_seconds')->label('Show after (seconds)')->numeric()->minValue(0)->maxValue(120)->default(2),
                DateTimePicker::make('starts_at')->label('Start showing at')->helperText('Leave empty to start immediately'),
                DateTimePicker::make('ends_at')->label('Stop showing at')->helperText('Leave empty to never expire'),
                TextInput::make('sort_order')->numeric()->default(0)->helperText('If several popups are live, the lowest number wins'),
                Toggle::make('is_active')->label('Active')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('image')->disk('public')->height(44),
            TextColumn::make('title')->searchable()->limit(45)->weight('bold'),
            TextColumn::make('frequency')->badge()->formatStateUsing(fn ($state) => Popup::FREQUENCIES[$state] ?? $state),
            TextColumn::make('starts_at')->dateTime('d M Y H:i')->placeholder('Immediately'),
            TextColumn::make('ends_at')->dateTime('d M Y H:i')->placeholder('Never'),
            IconColumn::make('is_active')->boolean()->label('Active'),
        ])
            ->defaultSort('sort_order')
            ->actions([
                ActionGroup::make([
                    EditAction::make()->modalHeading('EDIT POPUP')->modalWidth('lg'),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManagePopups::route('/')];
    }
}
