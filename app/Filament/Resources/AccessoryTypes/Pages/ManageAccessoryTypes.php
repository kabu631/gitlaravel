<?php

namespace App\Filament\Resources\AccessoryTypes\Pages;

use App\Filament\Resources\AccessoryTypes\AccessoryTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAccessoryTypes extends ManageRecords
{
    protected static string $resource = AccessoryTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
