<?php
namespace App\Filament\Resources\NewsArticleResource\Pages;
use App\Filament\Resources\NewsArticleResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;
class CreateNewsArticle extends CreateRecord {
    use HasActionsInsideFormCard;
 protected static string $resource = NewsArticleResource::class; }
