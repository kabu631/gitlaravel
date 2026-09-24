<?php

namespace App\Filament\Resources\MenuItemResource\Pages;

use App\Filament\Resources\MenuItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMenuItems extends ManageRecords
{
    protected static string $resource = MenuItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Menu Item')
                ->modalHeading('NEW MENU ITEM')
                ->modalSubmitActionLabel('CREATE ITEM')
                ->modalWidth('lg')
                ->createAnother(false),
        ];
    }
}
