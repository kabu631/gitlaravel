<?php

namespace App\Filament\Resources\SeoRedirectResource\Pages;

use App\Filament\Concerns\HasActionsInsideFormCard;
use App\Filament\Resources\SeoRedirectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSeoRedirect extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = SeoRedirectResource::class;
}
