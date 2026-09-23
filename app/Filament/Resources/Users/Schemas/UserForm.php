<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At'),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->minLength(8)
                    ->helperText(fn (string $context): string => $context === 'edit' ? 'Leave empty to keep existing password.' : 'Must be at least 8 characters.'),
                Select::make('roles')
                    ->label('Assigned Roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->placeholder('Select roles for this user')
                    ->helperText('Users inherit menu access and module permissions from their assigned roles.'),
                Toggle::make('is_admin')
                    ->label('Super Administrator (Full System Access)')
                    ->helperText('Grants unrestricted access across all modules and settings.'),
            ]);
    }
}

