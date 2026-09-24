<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobOpening extends Model
{
    protected $fillable = [
        'title', 'slug', 'department', 'location', 'type', 'summary', 'description',
        'apply_email', 'closes_at', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean', 'closes_at' => 'date'];

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            $m->slug = $m->slug ?: Str::slug($m->title);
            $base = $m->slug;
            for ($i = 2; static::where('slug', $m->slug)->exists(); $i++) {
                $m->slug = "{$base}-{$i}";
            }
        });
    }

    /** Active and not past its closing date. */
    public function scopeOpen($query)
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('closes_at')->orWhere('closes_at', '>=', today()))
            ->orderBy('sort_order')->latest('id');
    }
}
