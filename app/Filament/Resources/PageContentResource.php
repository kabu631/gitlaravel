<?php

namespace App\Filament\Resources;

use App\Filament\Schemas\SeoForm;
use App\Filament\Resources\PageContentResource\Pages;
use App\Models\PageContent;
use BackedEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use UnitEnum;

class PageContentResource extends Resource
{
    protected static ?string $model = PageContent::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';
    protected static UnitEnum|string|null $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Static Pages';
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Page Content Information')->columnSpanFull()->schema([
                Select::make('page')
                    ->options([
                        'about'    => 'About Us',
                        'contact'  => 'Contact',
                        'services' => 'Services',
                        'careers'  => 'Careers',
                        'terms'    => 'Terms & Conditions',
                        'privacy'  => 'Privacy Policy',
                    ])
                    ->required()
                    ->disabledOn('edit')
                    ->columnSpanFull(),

                TextInput::make('heading')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->placeholder('Page main heading (H1)'),

                TextInput::make('subheading')
                    ->maxLength(500)
                    ->columnSpanFull()
                    ->placeholder('Subtitle shown below the heading'),


                RichEditor::make('body')
                    ->columnSpanFull()
                    ->helperText('For Terms, Privacy, and Services — this is the main page content rendered as rich text.')
                    ->toolbarButtons([
                        'bold', 'italic', 'underline', 'strike',
                        'h2', 'h3',
                        'bulletList', 'orderedList',
                        'blockquote',
                        'link', 'undo', 'redo',
                    ]),

                Textarea::make('extra')
                    ->rows(10)
                    ->columnSpanFull()
                    ->helperText(
                        "Extra data as JSON. Examples:\n" .
                        "Contact page: {\"address\":\"Kathmandu, Nepal\",\"email\":\"info@gitinfosys.com\",\"phone\":\"+977 000 000 000\",\"hours\":\"Sun–Fri 9AM–6PM\",\"map_embed\":\"https://...(optional)\"}\n" .
                        "About page: {\"mission\":\"...\",\"vision\":\"...\",\"story\":\"...\"}\n" .
                        "Services page: leave empty or add bottom body text."
                    )
                    ->afterStateHydrated(function (Textarea $component, $state) {
                        if (is_array($state)) {
                            $component->state(json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                        }
                    })
                    ->dehydrateStateUsing(function ($state) {
                        if (!$state) return null;
                        $decoded = json_decode($state, true);
                        return is_array($decoded) ? $decoded : null;
                    }),
            ])->columns(1),

            SeoForm::section(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('page')
                ->formatStateUsing(fn($state) => match ($state) {
                    'about'    => 'About Us',
                    'contact'  => 'Contact',
                    'services' => 'Services',
                    'terms'    => 'Terms & Conditions',
                    'privacy'  => 'Privacy Policy',
                    default    => $state,
                })
                ->badge()
                ->color('primary'),
            TextColumn::make('heading')->limit(60)->placeholder('—'),
            TextColumn::make('subheading')->limit(60)->color('gray')->placeholder('—'),
            TextColumn::make('updated_at')->label('Last Updated')->dateTime('d M Y, H:i')->sortable(),
        ])
        ->actions([
\Filament\Actions\ActionGroup::make([EditAction::make()]),
])
        ->defaultSort('page');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPageContents::route('/'),
            'edit'  => Pages\EditPageContent::route('/{record}/edit'),
        ];
    }
}
