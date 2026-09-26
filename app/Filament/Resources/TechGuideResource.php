<?php

namespace App\Filament\Resources;

use App\Filament\Schemas\SeoForm;
use App\Filament\Resources\TechGuideResource\Pages;
use App\Models\TechGuide;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use BackedEnum;
use UnitEnum;

class TechGuideResource extends Resource
{
    protected static ?string $model = TechGuide::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Tech Guides';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Tech Guide Information')->columnSpanFull()->schema([
                TextInput::make('title')->required()->maxLength(255)->live(onBlur: true)
                    ->afterStateUpdated(fn($state, $set) => $set('slug', Str::slug($state)))->columnSpanFull(),
                SeoForm::slug('guides.show', required: true),

                // Type — drives the ?type= filter on the frontend
                Select::make('type')
                    ->label('Guide Type')
                    ->options([
                        'buying-guide' => '🛍️ Buying Guide',
                        'how-to'       => '🔧 How-To / Tutorial',
                    ])
                    ->placeholder('General (no specific type)')
                    ->nullable()
                    ->helperText('Buying Guide → shown under /guides?type=buying-guide · How-To → /guides?type=how-to · Leave blank for General.'),

                Select::make('user_id')
                    ->label('Author')
                    ->relationship('author', 'name')
                    ->default(fn() => auth()->id())
                    ->searchable()
                    ->preload()
                    ->nullable(),

                FileUpload::make('thumbnail')->image()->disk('public')->directory('guides')->nullable(),
                Toggle::make('is_published')->default(true),
                RichEditor::make('content')->required()->columnSpanFull(),
            ])->columns(2),

            SeoForm::section(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->searchable()->limit(40),
            TextColumn::make('type')
                ->badge()
                ->formatStateUsing(fn($state) => match($state) {
                    'buying-guide' => '🛍️ Buying Guide',
                    'how-to'       => '🔧 How-To',
                    default        => '📖 General',
                })
                ->color(fn($state) => match($state) {
                    'buying-guide' => 'success',
                    'how-to'       => 'info',
                    default        => 'gray',
                }),
            TextColumn::make('author.name')->label('Author')->sortable(),
            IconColumn::make('is_published')->boolean()->label('Published'),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ])->filters([
            SelectFilter::make('type')->options([
                'buying-guide' => 'Buying Guide',
                'how-to'       => 'How-To',
            ])->placeholder('All Types'),
            TernaryFilter::make('is_published')->label('Published'),
        ])->actions([
\Filament\Actions\ActionGroup::make([
            EditAction::make(),
            Action::make('view')
                ->label('View')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn($record) => route('guides.show', $record->slug))
                ->openUrlInNewTab(),
\Filament\Actions\DeleteAction::make(),
]),
])
          ->bulkActions([DeleteBulkAction::make()])
          ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTechGuides::route('/'),
            'create' => Pages\CreateTechGuide::route('/create'),
            'edit'   => Pages\EditTechGuide::route('/{record}/edit'),
        ];
    }
}
