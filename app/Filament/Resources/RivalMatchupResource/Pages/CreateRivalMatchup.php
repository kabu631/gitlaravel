<?php

namespace App\Filament\Resources\RivalMatchupResource\Pages;

use App\Filament\Resources\RivalMatchupResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;

class CreateRivalMatchup extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = RivalMatchupResource::class;
}
