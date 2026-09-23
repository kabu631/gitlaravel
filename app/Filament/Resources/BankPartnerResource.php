<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankPartnerResource\Pages;
use App\Models\BankPartner;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
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
use Illuminate\Support\Str;
use UnitEnum;

class BankPartnerResource extends Resource
{
    protected static ?string $model = BankPartner::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-credit-card';
    protected static UnitEnum|string|null $navigationGroup = 'Tech Lab & Tools';
    protected static ?string $navigationLabel = '0% EMI Banks';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Bank Partner Information')->schema([
                TextInput::make('name')
                    ->label('Commercial Bank Name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug($state)))
                    ->placeholder('e.g. Nabil Bank'),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(100)
                    ->unique(BankPartner::class, 'slug', ignoreRecord: true),

                FileUpload::make('logo')
                    ->label('Bank Logo (Optional)')
                    ->image()
                    ->disk('public')
                    ->directory('banks')
                    ->nullable(),

                CheckboxList::make('supported_tenures')
                    ->label('Supported EMI Tenures')
                    ->options([
                        3  => '3 Months',
                        6  => '6 Months',
                        9  => '9 Months',
                        12 => '12 Months',
                        18 => '18 Months',
                        24 => '24 Months',
                    ])
                    ->default([3, 6, 9, 12, 18])
                    ->columns(3),

                TextInput::make('processing_fee_percent')
                    ->label('Bank Processing Fee (%)')
                    ->numeric()
                    ->default(0.00)
                    ->prefix('%')
                    ->helperText('Usually 0% for zero-interest promotional partnerships.'),

                TextInput::make('min_downpayment_percent')
                    ->label('Minimum Down Payment (%)')
                    ->numeric()
                    ->default(0.00)
                    ->prefix('%')
                    ->helperText('e.g. 0% for full financing, or 10% / 15% required down payment.'),

                Textarea::make('terms_note')
                    ->label('Terms & Approval Note')
                    ->rows(2)
                    ->maxLength(300)
                    ->columnSpanFull()
                    ->placeholder('e.g. Requires an active credit card. Approval terms subject to bank guidelines.'),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower number = shown first'),

                Toggle::make('is_active')
                    ->label('Active Partner')
                    ->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('sort_order')->label('#')->sortable()->width('50px'),
            ImageColumn::make('logo')->disk('public')->height(30)->circular(),
            TextColumn::make('name')->label('Bank Name')->searchable()->weight('bold'),
            TextColumn::make('supported_tenures')
                ->label('Tenures')
                ->badge()
                ->formatStateUsing(fn($state) => is_array($state) ? implode('m, ', $state) . 'm' : $state)
                ->color('success'),
            TextColumn::make('min_downpayment_percent')->label('Min Downpay')->suffix('%'),
            IconColumn::make('is_active')->boolean()->label('Active'),
            TextColumn::make('updated_at')->label('Updated')->dateTime('d M Y')->sortable(),
        ])
        ->reorderable('sort_order')
        ->defaultSort('sort_order')
        ->actions([
            EditAction::make()
                ->modalHeading('EDIT BANK PARTNER')
                ->modalDescription('UPDATE 0% EMI BANK PARTNER DETAILS')
                ->modalSubmitActionLabel('SAVE CHANGES')
                ->modalWidth('lg'),
            \Filament\Actions\DeleteAction::make(),
        ])
        ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBankPartners::route('/'),
        ];
    }
}
