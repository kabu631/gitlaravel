<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserCommentResource\Pages;
use App\Models\UserComment;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class UserCommentResource extends Resource
{
    protected static ?string $model = UserComment::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Customer Comments';
    protected static ?int $navigationSort = 5;

    /** Surface the moderation queue size in the sidebar. */
    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Customer Comment')->schema([
                Select::make('gadget_id')
                    ->label('Product')
                    ->relationship('gadget', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('user_id')
                    ->label('Posted By')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('rating')
                    ->label('Rating (out of 10)')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10)
                    ->required(),

                Textarea::make('comment')
                    ->label('Comment')
                    ->rows(5)
                    ->maxLength(2000)
                    ->columnSpanFull()
                    ->helperText('Edit to redact, or delete the comment outright if it breaks the guidelines.'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('gadget.name')
                ->label('Product')
                ->searchable()
                ->sortable()
                ->limit(30),

            TextColumn::make('user.name')
                ->label('Customer')
                ->searchable()
                ->sortable(),

            TextColumn::make('rating')
                ->label('Rating')
                ->badge()
                ->formatStateUsing(fn($state) => $state . '/10')
                ->color(fn($state) => match (true) {
                    $state >= 8 => 'success',
                    $state >= 5 => 'warning',
                    default     => 'danger',
                })
                ->sortable(),

            TextColumn::make('comment')
                ->label('Comment')
                ->wrap()
                ->limit(90)
                ->searchable(),

            TextColumn::make('created_at')
                ->label('Posted')
                ->dateTime('M j, Y g:i A')
                ->sortable(),
        ])
        ->filters([
            SelectFilter::make('gadget_id')
                ->label('Product')
                ->relationship('gadget', 'name')
                ->searchable()
                ->preload(),

            Filter::make('low_rating')
                ->label('Low ratings only (below 5)')
                ->query(fn(Builder $query) => $query->where('rating', '<', 5)),
        ])
        ->defaultSort('created_at', 'desc')
        ->actions([EditAction::make(), DeleteAction::make()])
        ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserComments::route('/'),
            'edit'  => Pages\EditUserComment::route('/{record}/edit'),
        ];
    }
}
