<?php

namespace App\Filament\Resources\CameraShootoutResource\Pages;

use App\Filament\Resources\CameraShootoutResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCameraShootouts extends ListRecords
{
    protected static string $resource = CameraShootoutResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
