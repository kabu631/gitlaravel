<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::getAllSettings();
        return $all[$key] ?? $default;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): self
    {
        $encoded = is_array($value) ? json_encode($value) : (string) $value;
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $encoded, 'group' => $group]
        );

        Cache::forget('site_settings_all');
        return $setting;
    }

    public static function getAllSettings(): array
    {
        return Cache::remember('site_settings_all', 3600, function () {
            return static::pluck('value', 'key')->map(function ($val) {
                $decoded = json_decode($val, true);
                return json_last_error() === JSON_ERROR_NONE ? $decoded : $val;
            })->toArray();
        });
    }

    protected static function booted(): void
    {
        static::saved(fn() => Cache::forget('site_settings_all'));
        static::deleted(fn() => Cache::forget('site_settings_all'));
    }
}
