<?php

namespace App\Filament\Resources\BankPartnerResource\Pages;

use App\Filament\Resources\BankPartnerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBankPartners extends ManageRecords
{
    protected static string $resource = BankPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Bank Partner')
                ->modalHeading('NEW BANK PARTNER')
                ->modalDescription('DEFINE A 0% EMI BANK PARTNER')
                ->modalSubmitActionLabel('CREATE BANK PARTNER')
                ->modalWidth('lg')
                ->createAnother(false),
        ];
    }
}
