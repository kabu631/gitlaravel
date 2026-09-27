<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;

class SystemModule extends Model
{
    protected $fillable = [
        'name',
        'code',
        'route',
        'order',
        'icon',
        'status',
        'parent_id',
        'sub_parent_id',
        'show_in_menu',
    ];

    protected $casts = [
        'order' => 'integer',
        'show_in_menu' => 'boolean',
    ];

    public function setParentIdAttribute($value): void
    {
        $this->attributes['parent_id'] = blank($value) ? null : (int) $value;
    }

    public function setSubParentIdAttribute($value): void
    {
        $this->attributes['sub_parent_id'] = blank($value) ? null : (int) $value;
    }

    public function setRouteAttribute($value): void
    {
        $this->attributes['route'] = blank($value) ? null : trim($value);
    }

    public function setIconAttribute($value): void
    {
        $this->attributes['icon'] = blank($value) ? null : trim($value);
    }


    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function subParent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'sub_parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    public function subChildren(): HasMany
    {
        return $this->hasMany(self::class, 'sub_parent_id')->orderBy('order');
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_system_module')->withTimestamps();
    }


    public function getResolvedUrl(): ?string
    {
        if (blank($this->route)) {
            return null;
        }

        $route = trim($this->route);

        if (str_starts_with($route, 'http://') || str_starts_with($route, 'https://')) {
            return $route;
        }

        if (Route::has($route)) {
            return route($route);
        }

        $filamentPath = config('filament.path', 'secure-admin');
        $path = '/' . ltrim($route, '/');

        // Handle '/admin/...' placeholder convention by adapting to actual panel path
        if (str_starts_with($path, '/admin/') && $filamentPath !== 'admin') {
            $path = '/' . $filamentPath . substr($path, 6);
        } elseif ($path === '/admin' && $filamentPath !== 'admin') {
            $path = '/' . $filamentPath;
        }

        return url($path);
    }

    public function isActive(): bool
    {
        $resolvedUrl = $this->getResolvedUrl();
        if (! $resolvedUrl) {
            return false;
        }

        $currentPath = trim(request()->path(), '/');
        $modulePath = trim(parse_url($resolvedUrl, PHP_URL_PATH) ?? '', '/');

        if (blank($modulePath)) {
            return $currentPath === '';
        }

        return $currentPath === $modulePath || str_starts_with($currentPath, $modulePath . '/');
    }

    public static function getIconOptions(): array
    {
        return [
            'heroicon-o-globe-alt' => 'Globe',
            'heroicon-o-home' => 'Home',
            'heroicon-o-squares-2x2' => 'Grid / Dashboard',
            'heroicon-o-squares-plus' => 'System Modules',
            'heroicon-o-tag' => 'Tag / Category',
            'heroicon-o-cube' => 'Cube / Products',
            'heroicon-o-device-phone-mobile' => 'Device / Phone',
            'heroicon-o-building-office' => 'Building / Brands',
            'heroicon-o-folder' => 'Folder',
            'heroicon-o-shopping-bag' => 'Shopping / Orders',
            'heroicon-o-newspaper' => 'Newspaper / Articles',
            'heroicon-o-document-text' => 'Document / Content',
            'heroicon-o-photo' => 'Photo / Sliders',
            'heroicon-o-star' => 'Star / Reviews',
            'heroicon-o-chat-bubble-left-right' => 'Chat / Comments',
            'heroicon-o-book-open' => 'Book / Tech Guides',
            'heroicon-o-camera' => 'Camera Shootouts',
            'heroicon-o-scale' => 'Scale / Rival Matchup',
            'heroicon-o-signal' => 'Signal / Carrier Bands',
            'heroicon-o-wrench-screwdriver' => 'Wrench / Service Centers',
            'heroicon-o-credit-card' => 'Credit Card / Bank Partners',
            'heroicon-o-rocket-launch' => 'Rocket / Upcoming Launches',
            'heroicon-o-envelope' => 'Envelope / Messages',
            'heroicon-o-users' => 'Users / Accounts',
            'heroicon-o-cog-6-tooth' => 'Cog / Settings',
            'heroicon-o-shield-check' => 'Shield / Security',
            'heroicon-o-pencil-square' => 'Pencil / Blog Posts',
            'heroicon-o-megaphone' => 'Megaphone / Popups',
            'heroicon-o-briefcase' => 'Briefcase / Services & Jobs',
            'heroicon-o-user-group' => 'User Group / Team Members',
            'heroicon-o-bars-3' => 'Bars / Menu Items',
            'heroicon-o-phone' => 'Phone / Contact & Social',
            'heroicon-o-magnifying-glass-circle' => 'Magnifying Glass / SEO',
            'heroicon-o-adjustments-horizontal' => 'Adjustments / Settings',
            'heroicon-o-document-magnifying-glass' => 'Document Magnifying Glass / Page SEO',
            'heroicon-o-arrow-uturn-right' => 'Arrow / Redirects',
        ];
    }

    public static function seedDefaults(): void
    {
        (new \Database\Seeders\SystemModuleSeeder())->run();
    }
}
