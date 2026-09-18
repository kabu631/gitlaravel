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

        static::flushCache();
        return $setting;
    }

    /**
     * Groups that are safe to expose to the browser. Anything stored outside
     * these groups stays server-side, so a future private setting can't leak
     * just by being added in the admin panel.
     */
    public const PUBLIC_GROUPS = ['contact', 'social', 'announcement', 'cookie', 'mdms', 'general'];

    /** Settings shared with the frontend, keyed by group. */
    public static function publicSettings(): array
    {
        return Cache::remember('site_settings_public', 3600, function () {
            return static::whereIn('group', static::PUBLIC_GROUPS)
                ->get()
                ->groupBy('group')
                ->map(fn ($rows) => $rows->mapWithKeys(fn ($row) => [
                    $row->key => static::decode($row->value),
                ]))
                ->toArray();
        });
    }

    protected static function decode(?string $value): mixed
    {
        $decoded = json_decode((string) $value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
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

    public static function flushCache(): void
    {
        Cache::forget('site_settings_all');
        Cache::forget('site_settings_public');
    }

    protected static function booted(): void
    {
        static::saved(fn() => static::flushCache());
        static::deleted(fn() => static::flushCache());
    }
}
