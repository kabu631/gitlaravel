<?php
namespace App\Filament\Resources\TechGuideResource\Pages;
use App\Filament\Resources\TechGuideResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListTechGuides extends ListRecords {
    protected static string $resource = TechGuideResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
