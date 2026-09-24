<?php
namespace App\Filament\Resources\TechGuideResource\Pages;
use App\Filament\Resources\TechGuideResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;
class CreateTechGuide extends CreateRecord {
    use HasActionsInsideFormCard;
 protected static string $resource = TechGuideResource::class; }
