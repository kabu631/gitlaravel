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
                ->label('New EMI Bank')
                ->modalHeading('NEW EMI BANK')
                ->modalDescription('ADD A BANK THAT OFFERS 0% EMI')
                ->modalSubmitActionLabel('CREATE EMI BANK')
                ->modalWidth('lg')
                ->createAnother(false),
        ];
    }
}
