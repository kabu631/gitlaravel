<?php

namespace App\Filament\Resources\AuthorizedServiceCenterResource\Pages;

use App\Filament\Resources\AuthorizedServiceCenterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAuthorizedServiceCenter extends EditRecord
{
    protected static string $resource = AuthorizedServiceCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
