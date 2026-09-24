<?php
namespace App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;
class CreateBrand extends CreateRecord {
    use HasActionsInsideFormCard;
 protected static string $resource = BrandResource::class; }
