<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Gadget;
use App\Models\Review;
use BackedEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-star';
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('gadget_id')
                ->label('Gadget')
                ->options(Gadget::with('brand')->get()->mapWithKeys(fn($g) => [$g->id => "{$g->brand->name} {$g->name}"]))
                ->required()
                ->searchable(),
            Select::make('user_id')
                ->label('Author')
                ->relationship('author', 'name')
                ->required(),
            TextInput::make('title')->required()->maxLength(255)->live(onBlur: true)
                ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug($state)))->columnSpanFull(),
            TextInput::make('slug')->required()->maxLength(255),
            TextInput::make('rating')
                ->numeric()->minValue(0)->maxValue(10)->step(0.1)->required()
                ->helperText('0.0 – 10.0 · Reviews rated ≥ 8.0 qualify as "Editor\'s Choice" in the filter'),
            Toggle::make('is_published')->default(false),
            RichEditor::make('content')->required()->columnSpanFull(),
            Textarea::make('pros')->rows(4)->placeholder("One pro per line")->nullable(),
            Textarea::make('cons')->rows(4)->placeholder("One con per line")->nullable(),
            Textarea::make('verdict')->rows(3)->nullable()->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->searchable()->limit(40),
            TextColumn::make('gadget.name')->label('Gadget')->sortable(),
            TextColumn::make('author.name')->label('Author')->sortable(),
            TextColumn::make('rating')
                ->sortable()
                ->badge()
                ->color(fn($state) => $state >= 8 ? 'success' : ($state >= 6 ? 'warning' : 'danger'))
                ->formatStateUsing(fn($state) => $state >= 8 ? "⭐ {$state} Editor's Choice" : "★ {$state}"),
            IconColumn::make('is_published')->boolean()->label('Published'),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ])->filters([
            TernaryFilter::make('is_published')->label('Published'),
            SelectFilter::make('rating_tier')
                ->label('Quality Tier')
                ->options([
                    'editors' => "Editor's Choice (≥ 8.0)",
                    'good'    => 'Good (6.0 – 7.9)',
                    'fair'    => 'Fair (< 6.0)',
                ])
                ->query(fn($query, $state) => match($state['value'] ?? null) {
                    'editors' => $query->where('rating', '>=', 8),
                    'good'    => $query->whereBetween('rating', [6, 7.9]),
                    'fair'    => $query->where('rating', '<', 6),
                    default   => $query,
                }),
            SelectFilter::make('gadget')->relationship('gadget', 'name')->searchable(),
        ])->actions([
            EditAction::make(),
            Action::make('view')
                ->label('View')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn($record) => route('reviews.show', $record->slug))
                ->openUrlInNewTab(),
        ])
          ->bulkActions([DeleteBulkAction::make()])
          ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit'   => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
