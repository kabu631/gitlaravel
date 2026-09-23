<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('roles.name')
                    ->label('Assigned Roles')
                    ->badge()
                    ->color('primary')
                    ->placeholder('No role assigned')
                    ->separator(', '),
                IconColumn::make('is_admin')
                    ->label('Super Admin')
                    ->boolean()
                    ->alignCenter(),
                TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Filter by Role')
                    ->relationship('roles', 'name')
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('resetPassword')
                    ->label('Reset Password')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reset User Password')
                    ->modalDescription('Enter a new password for this user. They will be able to log in with this new password immediately.')
                    ->form([
                        TextInput::make('new_password')
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->label('New Password'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'password' => Hash::make($data['new_password']),
                        ]);
                    })
                    ->successNotificationTitle('Password reset successfully'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
