<?php

namespace App\Models;

use App\Traits\BacksUpImages;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    use BacksUpImages;

    protected array $imageBackupFields = ['image'];

    protected $fillable = [
        'title', 'body', 'image', 'badge', 'btn_text', 'btn_url',
        'delay_seconds', 'frequency', 'starts_at', 'ends_at', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    public const FREQUENCIES = [
        'always'           => 'Every page load',
        'once_per_session' => 'Once per browser session',
        'once_per_day'     => 'Once per day',
        'once'             => 'Only once per visitor',
    ];

    /** Active and inside its schedule window (if any). */
    public function scopeLive($query)
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->orderBy('sort_order')->latest('id');
    }
}
