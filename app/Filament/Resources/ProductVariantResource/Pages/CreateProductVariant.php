<?php
namespace App\Filament\Resources\ProductVariantResource\Pages;
use App\Filament\Concerns\HasActionsInsideFormCard;
use App\Filament\Resources\ProductVariantResource;
use Filament\Resources\Pages\CreateRecord;
class CreateProductVariant extends CreateRecord { use HasActionsInsideFormCard; protected static string $resource = ProductVariantResource::class; }
