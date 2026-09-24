<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;

class CreateContactMessage extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = ContactMessageResource::class;
}
