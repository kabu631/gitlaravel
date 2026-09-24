<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Concerns\HasActionsInsideFormCard;
use App\Filament\Resources\BlogPostResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogPost extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = BlogPostResource::class;
}
