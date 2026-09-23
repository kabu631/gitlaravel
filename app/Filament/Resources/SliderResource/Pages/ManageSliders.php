<?php

namespace App\Filament\Resources\SliderResource\Pages;

use App\Filament\Resources\SliderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSliders extends ManageRecords
{
    protected static string $resource = SliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Slider')
                ->modalHeading('NEW SLIDER')
                ->modalDescription('DEFINE A HOMEPAGE HERO BANNER SLIDE')
                ->modalSubmitActionLabel('CREATE SLIDER')
                ->modalWidth('lg')
                ->createAnother(false),
        ];
    }
}
