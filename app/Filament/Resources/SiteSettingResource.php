<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static UnitEnum|string|null $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Site & Layout Settings';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Configuration Key & Group')->schema([
                TextInput::make('key')
                    ->label('Setting Key Identifier')
                    ->required()
                    ->maxLength(100)
                    ->unique(SiteSetting::class, 'key', ignoreRecord: true)
                    ->helperText('e.g. announcement_text, footer_phone, cookie_consent_message, mdms_vat_percent'),

                Select::make('group')
                    ->label('Functional Area Group')
                    ->options([
                        'announcement' => '📢 Announcement Bar & Ticker',
                        'contact'      => '📞 Footer & Office Contact Info',
                        'social'       => '🌐 Social Media Channels',
                        'cookie'       => '🍪 Cookie Consent & Privacy',
                        'mdms'         => '🛡️ MDMS & Nepal Customs Tax',
                        'general'      => '⚙️ General Platform Config',
                    ])
                    ->required(),

                Textarea::make('value')
                    ->label('Setting Value (Text / URL / Number / JSON)')
                    ->rows(4)
                    ->columnSpanFull()
                    ->helperText('Enter the string, URL, numerical rate, or JSON configuration.')
                    ->required(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('group')
                ->label('Group')
                ->badge()
                ->color(fn($state) => match ($state) {
                    'announcement' => 'warning',
                    'contact'      => 'info',
                    'social'       => 'primary',
                    'cookie'       => 'success',
                    'mdms'         => 'danger',
                    default        => 'gray',
                })
                ->sortable(),

            TextColumn::make('key')
                ->label('Configuration Key')
                ->searchable()
                ->weight('bold')
                ->sortable(),

            TextColumn::make('value')
                ->label('Configured Value')
                ->limit(60)
                ->searchable(),

            TextColumn::make('updated_at')
                ->label('Last Modified')
                ->dateTime('d M Y, H:i')
                ->sortable(),
        ])
        ->filters([
            SelectFilter::make('group')
                ->options([
                    'announcement' => 'Announcement Bar',
                    'contact'      => 'Footer & Contact',
                    'social'       => 'Social Channels',
                    'cookie'       => 'Cookie Consent',
                    'mdms'         => 'MDMS & Customs',
                    'general'      => 'General',
                ]),
        ])
        ->defaultSort('group')
        ->actions([EditAction::make()])
        ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit'   => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
