<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RivalMatchup extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'category_slug',
        'device_a_id',
        'device_b_id',
        'metrics',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'metrics'    => 'array',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function deviceA()
    {
        return $this->belongsTo(Gadget::class, 'device_a_id');
    }

    public function deviceB()
    {
        return $this->belongsTo(Gadget::class, 'device_b_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
