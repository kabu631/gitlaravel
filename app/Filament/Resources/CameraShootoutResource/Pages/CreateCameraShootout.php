<?php

namespace App\Filament\Resources\CameraShootoutResource\Pages;

use App\Filament\Resources\CameraShootoutResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;

class CreateCameraShootout extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = CameraShootoutResource::class;
}
