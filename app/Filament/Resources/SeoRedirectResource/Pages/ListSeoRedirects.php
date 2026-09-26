<?php

namespace App\Filament\Resources\SeoRedirectResource\Pages;

use App\Filament\Resources\SeoRedirectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSeoRedirects extends ListRecords
{
    protected static string $resource = SeoRedirectResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
