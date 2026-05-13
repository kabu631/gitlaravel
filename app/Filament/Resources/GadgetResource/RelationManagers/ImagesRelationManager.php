<?php

namespace App\Filament\Resources\GadgetResource\RelationManagers;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    /**
     * Form for editing a single existing image record.
     */
    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            FileUpload::make('image')
                ->image()
                ->disk('public')
                ->directory('gadgets/gallery')
                ->imagePreviewHeight('150')
                ->required()
                ->columnSpanFull(),
            TextInput::make('alt_text')
                ->label('Alt Text')
                ->maxLength(255)
                ->nullable(),
            TextInput::make('order')
                ->numeric()
                ->default(0)
                ->label('Sort Order'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->square()
                    ->size(80),
                TextColumn::make('alt_text')
                    ->label('Alt Text')
                    ->placeholder('—'),
                TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->headerActions([
                // ── Upload multiple images in one go ──────────────────────
                Action::make('uploadMultiple')
                    ->label('Upload Images')
                    ->icon('heroicon-o-photo')
                    ->color('primary')
                    ->form([
                        FileUpload::make('images')
                            ->label('Select Images (you can pick multiple)')
                            ->image()
                            ->multiple()
                            ->disk('public')
                            ->directory('gadgets/gallery')
                            ->imagePreviewHeight('120')
                            ->reorderable()
                            ->appendFiles()
                            ->minFiles(1)
                            ->maxFiles(20)
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('start_order')
                            ->label('Starting Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Images will be numbered sequentially from this value.'),
                    ])
                    ->action(function (array $data): void {
                        $gadget     = $this->getOwnerRecord();
                        $order      = (int) ($data['start_order'] ?? 0);

                        foreach ((array) $data['images'] as $path) {
                            $gadget->images()->create([
                                'image'    => $path,
                                'alt_text' => null,
                                'order'    => $order++,
                            ]);
                        }
                    }),

                // ── Single image (keep the classic row-by-row option) ─────
                CreateAction::make()
                    ->label('Add Single Image'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([DeleteBulkAction::make()])
            ->defaultSort('order');
    }
}
