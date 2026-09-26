<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeoRedirectResource\Pages;
use App\Models\SeoRedirect;
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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class SeoRedirectResource extends Resource
{
    protected static ?string $model = SeoRedirect::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-arrow-uturn-right';
    protected static UnitEnum|string|null $navigationGroup = 'SEO';
    protected static ?string $navigationLabel = 'URL Redirects';
    protected static ?string $modelLabel = 'redirect';
    protected static ?string $slug = 'seo-redirects';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Redirect')
                ->description('Used only when the old URL no longer exists. Slug changes on products, brands, posts, news, guides and reviews are added here automatically.')
                ->columnSpanFull()->columns(2)->schema([
                    TextInput::make('from_path')->label('Old URL path')->required()->maxLength(255)
                        ->placeholder('/blog/old-post-slug')
                        ->unique(ignoreRecord: true)
                        ->regex('#^/?[^\s?]*$#')
                        ->validationMessages(['regex' => 'Enter a path only, like /blog/old-post (no domain or query string).']),
                    TextInput::make('to_path')->label('New URL')->required()->maxLength(255)
                        ->placeholder('/blog/new-post-slug or https://…'),
                    Select::make('status_code')->label('Type')->options([
                        301 => '301 — Permanent (recommended)',
                        302 => '302 — Temporary',
                    ])->default(301)->required()->selectablePlaceholder(false),
                    Toggle::make('is_active')->label('Active')->default(true)->inline(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('from_path')->label('From')->searchable()->weight('bold')->copyable(),
            TextColumn::make('to_path')->label('To')->searchable()->limit(50),
            TextColumn::make('status_code')->label('Type')->badge(),
            IconColumn::make('is_active')->boolean()->label('Active'),
            TextColumn::make('hits')->sortable(),
            TextColumn::make('last_hit_at')->label('Last used')->since()->placeholder('never')->sortable(),
            TextColumn::make('created_at')->dateTime('d M Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([TernaryFilter::make('is_active')->label('Active')])
            ->defaultSort('created_at', 'desc')
            ->actions([ActionGroup::make([EditAction::make(), DeleteAction::make()])])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSeoRedirects::route('/'),
            'create' => Pages\CreateSeoRedirect::route('/create'),
            'edit'   => Pages\EditSeoRedirect::route('/{record}/edit'),
        ];
    }
}
