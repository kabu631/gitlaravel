<?php

namespace App\Filament\Resources\CarrierFrequencyBandResource\Pages;

use App\Filament\Resources\CarrierFrequencyBandResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCarrierFrequencyBand extends EditRecord
{
    protected static string $resource = CarrierFrequencyBandResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
