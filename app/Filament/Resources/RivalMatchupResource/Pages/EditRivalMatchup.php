<?php

namespace App\Filament\Resources\RivalMatchupResource\Pages;

use App\Filament\Resources\RivalMatchupResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRivalMatchup extends EditRecord
{
    protected static string $resource = RivalMatchupResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
