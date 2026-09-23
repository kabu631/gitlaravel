<?php

namespace App\Filament\Resources\AuthorizedServiceCenterResource\Pages;

use App\Filament\Resources\AuthorizedServiceCenterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAuthorizedServiceCenters extends ManageRecords
{
    protected static string $resource = AuthorizedServiceCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Service Center')
                ->modalHeading('NEW SERVICE CENTER')
                ->modalDescription('DEFINE AN AUTHORIZED REPAIR & SERVICE CENTER')
                ->modalSubmitActionLabel('CREATE SERVICE CENTER')
                ->modalWidth('lg')
                ->createAnother(false),
        ];
    }
}
