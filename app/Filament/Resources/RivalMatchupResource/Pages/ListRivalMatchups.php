<?php

namespace App\Filament\Resources\RivalMatchupResource\Pages;

use App\Filament\Resources\RivalMatchupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRivalMatchups extends ListRecords
{
    protected static string $resource = RivalMatchupResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
