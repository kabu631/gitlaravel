<?php

namespace App\Filament\Resources\BankPartnerResource\Pages;

use App\Filament\Resources\BankPartnerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBankPartner extends EditRecord
{
    protected static string $resource = BankPartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
