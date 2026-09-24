<?php
namespace App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;
class CreateCategory extends CreateRecord {
    use HasActionsInsideFormCard;
 protected static string $resource = CategoryResource::class; }
