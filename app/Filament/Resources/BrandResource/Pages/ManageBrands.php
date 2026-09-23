<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBrands extends ManageRecords
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Brand')
                ->modalHeading('New Brand')
                ->modalDescription('Define a new product brand')
                ->modalSubmitActionLabel('Create brand')
                ->modalWidth('md')
                ->createAnother(false),
        ];
    }
}
