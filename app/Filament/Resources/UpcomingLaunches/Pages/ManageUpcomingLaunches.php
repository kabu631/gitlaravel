<?php

namespace App\Filament\Resources\UpcomingLaunches\Pages;

use App\Filament\Resources\UpcomingLaunches\UpcomingLaunchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUpcomingLaunches extends ManageRecords
{
    protected static string $resource = UpcomingLaunchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Upcoming Launch')
                ->modalHeading('NEW UPCOMING LAUNCH')
                ->modalDescription('DEFINE A NEW UPCOMING GADGET LAUNCH')
                ->modalSubmitActionLabel('CREATE LAUNCH')
                ->modalWidth('lg')
                ->createAnother(false),
        ];
    }
}
