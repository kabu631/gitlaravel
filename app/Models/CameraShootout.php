<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CameraShootout extends Model
{
    protected $fillable = [
        'title',
        'description',
        'icon',
        'device_a_name',
        'device_a_specs',
        'device_a_exif',
        'device_a_image',
        'device_a_device_image',
        'phone_a_votes',
        'device_b_name',
        'device_b_specs',
        'device_b_exif',
        'device_b_image',
        'device_b_device_image',
        'phone_b_votes',
        'winner_summary',
        'editorial_deep_dive',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'phone_a_votes' => 'integer',
        'phone_b_votes' => 'integer',
        'sort_order'    => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getTotalVotesAttribute(): int
    {
        return $this->phone_a_votes + $this->phone_b_votes;
    }

    public function getPhoneAPercentAttribute(): int
    {
        $total = $this->total_votes;
        if ($total === 0) return 50;
        return (int) round(($this->phone_a_votes / $total) * 100);
    }

    public function getPhoneBPercentAttribute(): int
    {
        $total = $this->total_votes;
        if ($total === 0) return 50;
        return 100 - $this->phone_a_percent;
    }
}
