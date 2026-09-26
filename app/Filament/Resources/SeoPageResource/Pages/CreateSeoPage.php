<?php

namespace App\Filament\Resources\SeoPageResource\Pages;

use App\Filament\Concerns\HasActionsInsideFormCard;
use App\Filament\Resources\SeoPageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSeoPage extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = SeoPageResource::class;
}
