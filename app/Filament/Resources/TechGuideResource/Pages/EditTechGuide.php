<?php
namespace App\Filament\Resources\TechGuideResource\Pages;
use App\Filament\Resources\TechGuideResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditTechGuide extends EditRecord {
    protected static string $resource = TechGuideResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
