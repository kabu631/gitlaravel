<?php

namespace App\Filament\Resources\PopupResource\Pages;

use App\Filament\Resources\PopupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePopups extends ManageRecords
{
    protected static string $resource = PopupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Popup')
                ->modalHeading('NEW POPUP')
                ->modalSubmitActionLabel('CREATE POPUP')
                ->modalWidth('lg')
                ->createAnother(false),
        ];
    }
}
