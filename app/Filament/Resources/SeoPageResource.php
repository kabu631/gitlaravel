<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeoPageResource\Pages;
use App\Filament\Schemas\SeoForm;
use App\Models\SeoMeta;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

/** SEO for listing & static pages that have no record of their own (home, /blog, /products …). */
class SeoPageResource extends Resource
{
    protected static ?string $model = SeoMeta::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static UnitEnum|string|null $navigationGroup = 'SEO';
    protected static ?string $navigationLabel = 'Page SEO';
    protected static ?string $modelLabel = 'page SEO';
    protected static ?string $pluralModelLabel = 'page SEO';
    protected static ?string $slug = 'seo-pages';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNotNull('route_name');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Page')->columnSpanFull()->schema([
                Select::make('route_name')
                    ->label('Frontend page')
                    ->options(SeoMeta::PAGE_ROUTES)
                    ->required()
                    ->searchable()
                    ->unique(ignoreRecord: true)
                    ->helperText(fn (?string $state) => $state ? route($state) : 'Pick the page these settings apply to.'),
            ]),
            Section::make('SEO')->columnSpanFull()->schema(SeoForm::fields()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('route_name')->label('Page')
                ->formatStateUsing(fn (string $state) => SeoMeta::PAGE_ROUTES[$state] ?? $state)
                ->description(fn (SeoMeta $record) => route($record->route_name, absolute: false))
                ->searchable()->weight('bold'),
            TextColumn::make('meta_title')->label('Meta title')->limit(50)->placeholder('—'),
            TextColumn::make('meta_description')->label('Meta description')->limit(60)->placeholder('—')->toggleable(),
            TextColumn::make('robots')->badge()->placeholder('default')
                ->color(fn (?string $state) => str_starts_with((string) $state, 'noindex') ? 'danger' : 'success'),
            TextColumn::make('updated_at')->dateTime('d M Y')->sortable(),
        ])
            ->defaultSort('route_name')
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('view')->label('View page')->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(fn (SeoMeta $record) => route($record->route_name))->openUrlInNewTab(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSeoPages::route('/'),
            'create' => Pages\CreateSeoPage::route('/create'),
            'edit'   => Pages\EditSeoPage::route('/{record}/edit'),
        ];
    }
}
