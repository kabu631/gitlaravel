<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BankPartner extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'supported_tenures',
        'processing_fee_percent',
        'min_downpayment_percent',
        'terms_note',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'supported_tenures'       => 'array',
        'processing_fee_percent'  => 'decimal:2',
        'min_downpayment_percent' => 'decimal:2',
        'is_active'               => 'boolean',
        'sort_order'              => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($bank) {
            if (empty($bank->slug)) {
                $bank->slug = Str::slug($bank->name);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
