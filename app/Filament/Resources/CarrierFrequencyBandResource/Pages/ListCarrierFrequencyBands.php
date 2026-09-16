<?php

namespace App\Filament\Resources\CarrierFrequencyBandResource\Pages;

use App\Filament\Resources\CarrierFrequencyBandResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCarrierFrequencyBands extends ListRecords
{
    protected static string $resource = CarrierFrequencyBandResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
