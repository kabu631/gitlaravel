<?php

namespace App\Filament\Resources\UpcomingLaunches\Pages;

use App\Filament\Resources\UpcomingLaunches\UpcomingLaunchResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUpcomingLaunch extends EditRecord
{
    protected static string $resource = UpcomingLaunchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
