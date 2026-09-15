<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpcomingLaunch extends Model
{
    protected $fillable = [
        'name', 'brand', 'category', 'expected_date', 'est_price',
        'confidence', 'badge', 'highlight', 'tag_color', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'confidence' => 'integer',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($m) {
            $m->category ??= 'Smartphones';
            $m->est_price ??= 'TBA';
            $m->confidence ??= 85;
            $m->tag_color ??= 'emerald';
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
