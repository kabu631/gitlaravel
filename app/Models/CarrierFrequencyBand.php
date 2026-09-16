<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarrierFrequencyBand extends Model
{
    protected $fillable = [
        'carrier',
        'code',
        'technology',
        'frequency',
        'role',
        'status_badge',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
