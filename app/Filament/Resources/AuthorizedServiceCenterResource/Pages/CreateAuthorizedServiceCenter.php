<?php

namespace App\Filament\Resources\AuthorizedServiceCenterResource\Pages;

use App\Filament\Resources\AuthorizedServiceCenterResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;

class CreateAuthorizedServiceCenter extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = AuthorizedServiceCenterResource::class;
}
