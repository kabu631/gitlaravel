<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoRedirect extends Model
{
    protected $fillable = ['from_path', 'to_path', 'status_code', 'is_active', 'hits', 'last_hit_at'];

    protected $casts = [
        'is_active'   => 'boolean',
        'last_hit_at' => 'datetime',
    ];

    public static function normalize(string $path): string
    {
        $path = parse_url($path, PHP_URL_PATH) ?: '/';

        return '/' . trim($path, '/');
    }

    /** Record an old → new URL move, collapsing chains so old links never hop twice. */
    public static function record(string $from, string $to): void
    {
        $from = static::normalize($from);
        $to   = static::normalize($to);

        if ($from === $to) {
            return;
        }

        static::where('to_path', $from)->update(['to_path' => $to]);
        static::where('from_path', $to)->delete();
        static::updateOrCreate(['from_path' => $from], ['to_path' => $to, 'status_code' => 301, 'is_active' => true]);
    }

    public static function findFor(string $path): ?self
    {
        return static::where('from_path', static::normalize($path))->where('is_active', true)->first();
    }

    protected static function booted(): void
    {
        static::saving(function (self $r) {
            $r->from_path = static::normalize($r->from_path);
            if (! preg_match('#^https?://#', $r->to_path)) {
                $r->to_path = static::normalize($r->to_path);
            }
        });
    }
}
