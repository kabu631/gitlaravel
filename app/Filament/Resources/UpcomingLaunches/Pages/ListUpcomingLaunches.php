<?php

namespace App\Filament\Resources\UpcomingLaunches\Pages;

use App\Filament\Resources\UpcomingLaunches\UpcomingLaunchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUpcomingLaunches extends ListRecords
{
    protected static string $resource = UpcomingLaunchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
