<?php

namespace App\Filament\Resources\CarrierFrequencyBandResource\Pages;

use App\Filament\Resources\CarrierFrequencyBandResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCarrierFrequencyBands extends ManageRecords
{
    protected static string $resource = CarrierFrequencyBandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Frequency Band')
                ->modalHeading('NEW FREQUENCY BAND')
                ->modalDescription('DEFINE A 5G OR 4G CARRIER FREQUENCY BAND')
                ->modalSubmitActionLabel('CREATE FREQUENCY BAND')
                ->modalWidth('lg')
                ->createAnother(false),
        ];
    }
}
