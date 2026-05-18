<?php

namespace App\Models;

use App\Traits\BacksUpImages;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use BacksUpImages;

    protected array $imageBackupFields = ['image'];

    protected $fillable = [
        'title', 'subtitle', 'description', 'image', 'badge',
        'btn1_text', 'btn1_url', 'btn1_style',
        'btn2_text', 'btn2_url', 'btn2_style',
        'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
