<?php

namespace App\Filament\Resources\UpcomingLaunches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UpcomingLaunchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('brand')
                    ->required(),
                TextInput::make('category')
                    ->required(),
                TextInput::make('expected_date')
                    ->required(),
                TextInput::make('est_price')
                    ->required(),
                TextInput::make('confidence')
                    ->required()
                    ->numeric()
                    ->default(90),
                TextInput::make('badge')
                    ->default(null),
                Textarea::make('highlight')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('tag_color')
                    ->required()
                    ->default('emerald'),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
