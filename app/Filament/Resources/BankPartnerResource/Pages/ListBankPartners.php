<?php

namespace App\Filament\Resources\BankPartnerResource\Pages;

use App\Filament\Resources\BankPartnerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBankPartners extends ListRecords
{
    protected static string $resource = BankPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
