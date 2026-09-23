<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Models\Role;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageRoles extends ManageRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Role')
                ->modalHeading('Create New Role')
                ->modalDescription('Define a user role and assign accessible dashboard modules.')
                ->modalSubmitActionLabel('Create Role')
                ->stickyModalHeader()
                ->stickyModalFooter()
                ->modalWidth('4xl')
                ->createAnother(false),

            Action::make('syncDefaultRoles')
                ->label('Restore Default Roles')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Restore Default Roles & Permissions')
                ->modalDescription('This will verify and restore standard default roles (Super Admin, Catalog Manager, Content Editor, Tech Lab Specialist, Sales Manager) and assign standard module permissions.')
                ->modalSubmitActionLabel('Restore Defaults')
                ->action(function () {
                    Role::seedDefaultRoles();
                    Notification::make()
                        ->title('Default roles and permissions synchronized successfully.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
