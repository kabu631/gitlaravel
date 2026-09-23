<?php

namespace App\Filament\Resources\SystemModuleResource\Pages;

use App\Filament\Resources\SystemModuleResource;
use App\Models\SystemModule;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageSystemModules extends ManageRecords
{
    protected static string $resource = SystemModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add New System Module')
                ->modalHeading('Add New System Module')
                ->modalDescription('Create a new menu item and choose where it appears in the navigation.')
                ->modalSubmitActionLabel('Save menu module')
                ->stickyModalHeader()
                ->stickyModalFooter()
                ->modalWidth('2xl')
                ->createAnother(false)
                ->mutateFormDataUsing(function (array $data): array {
                    if (blank($data['parent_id'] ?? null)) {
                        $data['parent_id'] = null;
                    }
                    if (blank($data['sub_parent_id'] ?? null)) {
                        $data['sub_parent_id'] = null;
                    }
                    if (blank($data['route'] ?? null)) {
                        $data['route'] = null;
                    }
                    if (blank($data['icon'] ?? null)) {
                        $data['icon'] = null;
                    }
                    return $data;
                }),

            Action::make('syncDefaults')
                ->label('Sync Default Modules')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Sync Default System Modules')
                ->modalDescription('This will verify and restore all default admin modules (Catalog, Content, Tools, Settings, etc.) in the database.')
                ->modalSubmitActionLabel('Sync Now')
                ->action(function () {
                    SystemModule::seedDefaults();
                    Notification::make()
                        ->title('Default modules synchronized successfully.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
