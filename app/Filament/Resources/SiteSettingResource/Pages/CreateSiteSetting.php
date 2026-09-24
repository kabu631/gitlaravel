<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;

class CreateSiteSetting extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = SiteSettingResource::class;
}
