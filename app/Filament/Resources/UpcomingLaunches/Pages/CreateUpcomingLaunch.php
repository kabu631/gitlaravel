<?php

namespace App\Filament\Resources\UpcomingLaunches\Pages;

use App\Filament\Resources\UpcomingLaunches\UpcomingLaunchResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;

class CreateUpcomingLaunch extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = UpcomingLaunchResource::class;
}
