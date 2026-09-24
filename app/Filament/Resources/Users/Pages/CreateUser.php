<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = UserResource::class;
}
