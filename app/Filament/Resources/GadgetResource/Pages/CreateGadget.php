<?php
namespace App\Filament\Resources\GadgetResource\Pages;
use App\Filament\Resources\GadgetResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;
class CreateGadget extends CreateRecord {
    use HasActionsInsideFormCard;
 protected static string $resource = GadgetResource::class; }
