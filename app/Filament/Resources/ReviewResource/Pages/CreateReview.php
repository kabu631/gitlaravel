<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;

class CreateReview extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = ReviewResource::class;
}
