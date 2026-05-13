<?php
namespace App\Filament\Resources\GadgetResource\Pages;
use App\Filament\Resources\GadgetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListGadgets extends ListRecords {
    protected static string $resource = GadgetResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
