<?php

namespace App\Filament\Resources\CameraShootoutResource\Pages;

use App\Filament\Resources\CameraShootoutResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCameraShootout extends EditRecord
{
    protected static string $resource = CameraShootoutResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
