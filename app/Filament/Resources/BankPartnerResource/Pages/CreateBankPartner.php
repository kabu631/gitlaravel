<?php

namespace App\Filament\Resources\BankPartnerResource\Pages;

use App\Filament\Resources\BankPartnerResource;
use App\Filament\Concerns\HasActionsInsideFormCard;
use Filament\Resources\Pages\CreateRecord;

class CreateBankPartner extends CreateRecord
{
    use HasActionsInsideFormCard;

    protected static string $resource = BankPartnerResource::class;
}
