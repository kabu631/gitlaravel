<?php

namespace App\Filament\Resources\SliderResource\Pages;

use App\Filament\Resources\SliderResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;

class CreateSlider extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = SliderResource::class;
}
