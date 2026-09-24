<?php

namespace App\Filament\Resources\CarrierFrequencyBandResource\Pages;

use App\Filament\Resources\CarrierFrequencyBandResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;

class CreateCarrierFrequencyBand extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = CarrierFrequencyBandResource::class;
}
