<?php

namespace App\Filament\Resources\UserCommentResource\Pages;

use App\Filament\Resources\UserCommentResource;
use Filament\Resources\Pages\ListRecords;

class ListUserComments extends ListRecords
{
    protected static string $resource = UserCommentResource::class;

    // Comments originate from customers on the storefront, so there is no
    // "create" action here -- this page exists for moderation.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
