<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CameraShootoutResource\Pages;
use App\Models\CameraShootout;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CameraShootoutResource extends Resource
{
    protected static ?string $model = CameraShootout::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-camera';
    protected static UnitEnum|string|null $navigationGroup = 'Tech Lab & Tools';
    protected static ?string $navigationLabel = 'Camera Shootouts';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Challenge Round Information')->schema([
                TextInput::make('title')
                    ->label('Round Title')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. Kathmandu Night Street Test'),

                Select::make('icon')
                    ->label('Round Theme Icon')
                    ->options([
                        'Moon'       => '🌙 Night / Low Light (Moon)',
                        'Sun'        => '☀️ Daylight Portrait (Sun)',
                        'Eye'        => '👁️ Telephoto / Zoom (Eye)',
                        'Camera'     => '📷 General Shootout (Camera)',
                        'Sparkles'   => '✨ Macro / Details (Sparkles)',
                        'Smartphone' => '📱 Flagship Duel (Smartphone)',
                    ])
                    ->default('Camera')
                    ->required(),

                Textarea::make('description')
                    ->label('Scene & Challenge Description')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull()
                    ->placeholder('Describe the testing environment, lighting, and what qualities users should judge...'),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower number = shown first'),

                Toggle::make('is_active')
                    ->label('Active / Published')
                    ->default(true),
            ])->columns(2),

            Section::make('Mystery Phone Alpha (Device A)')->schema([
                TextInput::make('device_a_name')
                    ->label('Actual Phone Name (Revealed After Voting)')
                    ->required()
                    ->placeholder('e.g. Apple iPhone 15 Pro Max'),

                TextInput::make('device_a_specs')
                    ->label('Hardware Camera Specs')
                    ->placeholder('e.g. 48MP (1/1.28", 24mm, f/1.78, Sensor-shift OIS)'),

                TextInput::make('device_a_exif')
                    ->label('Sample Shot EXIF Watermark')
                    ->placeholder('e.g. f/1.78 · 1/25s · ISO 1250'),

                TextInput::make('phone_a_votes')
                    ->label('Vote Count')
                    ->numeric()
                    ->default(0),

                FileUpload::make('device_a_image')
                    ->label('Sample Shootout Photo (Uncompressed Shot)')
                    ->image()
                    ->disk('public')
                    ->directory('shootout')
                    ->required()
                    ->helperText('The blind photo users evaluate.'),

                FileUpload::make('device_a_device_image')
                    ->label('Device Hardware Photo (Optional)')
                    ->image()
                    ->disk('public')
                    ->directory('gadgets')
                    ->nullable()
                    ->helperText('Shown when device is revealed.'),
            ])->columns(2)->collapsible(),

            Section::make('Mystery Phone Beta (Device B)')->schema([
                TextInput::make('device_b_name')
                    ->label('Actual Phone Name (Revealed After Voting)')
                    ->required()
                    ->placeholder('e.g. Samsung Galaxy S24 Ultra'),

                TextInput::make('device_b_specs')
                    ->label('Hardware Camera Specs')
                    ->placeholder('e.g. 200MP (1/1.3", 24mm, f/1.7, Multi-directional PDAF, OIS)'),

                TextInput::make('device_b_exif')
                    ->label('Sample Shot EXIF Watermark')
                    ->placeholder('e.g. f/1.70 · 1/20s · ISO 800'),

                TextInput::make('phone_b_votes')
                    ->label('Vote Count')
                    ->numeric()
                    ->default(0),

                FileUpload::make('device_b_image')
                    ->label('Sample Shootout Photo (Uncompressed Shot)')
                    ->image()
                    ->disk('public')
                    ->directory('shootout')
                    ->required()
                    ->helperText('The blind photo users evaluate.'),

                FileUpload::make('device_b_device_image')
                    ->label('Device Hardware Photo (Optional)')
                    ->image()
                    ->disk('public')
                    ->directory('gadgets')
                    ->nullable()
                    ->helperText('Shown when device is revealed.'),
            ])->columns(2)->collapsible(),

            Section::make('Editorial & Lab Analysis (Shown After User Votes)')->schema([
                TextInput::make('winner_summary')
                    ->label('Winner Verdict Headline')
                    ->columnSpanFull()
                    ->placeholder('e.g. Device A won with 59% of votes!'),

                Textarea::make('editorial_deep_dive')
                    ->label('Expert Lab Deep Dive Breakdown')
                    ->rows(3)
                    ->columnSpanFull()
                    ->placeholder('Explain why one phone won, dynamic range highlights, lens flare suppression, sharpening artifacts, etc.'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('sort_order')->label('#')->sortable()->width('50px'),
            ImageColumn::make('device_a_image')->label('Sample A')->disk('public')->height(40),
            ImageColumn::make('device_b_image')->label('Sample B')->disk('public')->height(40),
            TextColumn::make('title')->label('Challenge')->searchable()->weight('bold'),
            TextColumn::make('device_a_name')->label('Device A')->limit(20),
            TextColumn::make('device_b_name')->label('Device B')->limit(20),
            TextColumn::make('votes_summary')
                ->label('Votes (A / B)')
                ->state(fn($record) => "{$record->phone_a_votes} ({$record->phone_a_percent}%) / {$record->phone_b_votes} ({$record->phone_b_percent}%)")
                ->badge()
                ->color('info'),
            IconColumn::make('is_active')->boolean()->label('Active'),
        ])
        ->reorderable('sort_order')
        ->defaultSort('sort_order')
        ->actions([
            EditAction::make(),
            Action::make('reset_votes')
                ->label('Reset Votes')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function (CameraShootout $record) {
                    $record->update(['phone_a_votes' => 0, 'phone_b_votes' => 0]);
                }),
        ])
        ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCameraShootouts::route('/'),
            'create' => Pages\CreateCameraShootout::route('/create'),
            'edit'   => Pages\EditCameraShootout::route('/{record}/edit'),
        ];
    }
}
