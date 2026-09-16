<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorizedServiceCenter extends Model
{
    protected $fillable = [
        'brand',
        'name',
        'address',
        'city',
        'phone',
        'email',
        'avg_screen_cost',
        'is_authorized',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_authorized' => 'boolean',
        'is_active'     => 'boolean',
        'sort_order'    => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
