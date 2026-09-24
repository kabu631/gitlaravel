<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterSubscriberResource\Pages;
use App\Models\NewsletterSubscriber;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-envelope';
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Newsletter Subscribers';
    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            Toggle::make('is_active')->label('Subscribed')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('email')->searchable()->copyable()->weight('bold'),
            IconColumn::make('is_active')->boolean()->label('Subscribed'),
            TextColumn::make('ip_address')->label('IP')->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('created_at')->label('Joined')->dateTime('d M Y H:i')->sortable(),
        ])
            ->filters([TernaryFilter::make('is_active')->label('Subscribed')])
            ->defaultSort('created_at', 'desc')
            ->actions([ActionGroup::make([DeleteAction::make()])])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageNewsletterSubscribers::route('/')];
    }
}
