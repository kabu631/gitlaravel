<?php

namespace App\Filament\Resources\JobOpeningResource\Pages;

use App\Filament\Resources\JobOpeningResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageJobOpenings extends ManageRecords
{
    protected static string $resource = JobOpeningResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Job Opening')
                ->modalHeading('NEW JOB OPENING')
                ->modalSubmitActionLabel('PUBLISH')
                ->modalWidth('2xl')
                ->createAnother(false),
        ];
    }
}
