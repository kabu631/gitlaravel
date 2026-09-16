<?php

namespace App\Filament\Resources\AuthorizedServiceCenterResource\Pages;

use App\Filament\Resources\AuthorizedServiceCenterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuthorizedServiceCenters extends ListRecords
{
    protected static string $resource = AuthorizedServiceCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
