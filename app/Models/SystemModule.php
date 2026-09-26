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
            'heroicon-o-chart-bar' => 'Analytics / Reports',
        ];
    }

    public static function seedDefaults(): void
    {
        $filamentPath = config('filament.path', 'secure-admin');

        $structure = [
            [
                'name' => 'Dashboard',
                'code' => 'dashboard',
                'route' => "/{$filamentPath}",
                'order' => 1,
                'icon' => 'heroicon-o-home',
                'status' => 'active',
                'show_in_menu' => true,
                'children' => [],
            ],
            [
                'name' => 'Catalog',
                'code' => 'catalog',
                'route' => null,
                'order' => 2,
                'icon' => 'heroicon-o-tag',
                'status' => 'active',
                'show_in_menu' => true,
                'children' => [
                    ['name' => 'Gadgets', 'code' => 'gadgets', 'route' => "/{$filamentPath}/gadgets", 'order' => 1, 'icon' => 'heroicon-o-device-phone-mobile'],
                    ['name' => 'Product Variants', 'code' => 'product_variants', 'route' => "/{$filamentPath}/product-variants", 'order' => 2, 'icon' => 'heroicon-o-squares-2x2'],
                    ['name' => 'Brands', 'code' => 'brands', 'route' => "/{$filamentPath}/brands", 'order' => 3, 'icon' => 'heroicon-o-building-office'],
                    ['name' => 'Categories', 'code' => 'categories', 'route' => "/{$filamentPath}/categories", 'order' => 4, 'icon' => 'heroicon-o-folder'],
                    ['name' => 'Accessory Types', 'code' => 'accessory_types', 'route' => "/{$filamentPath}/accessory-types", 'order' => 5, 'icon' => 'heroicon-o-tag'],
                ],
            ],
            [
                'name' => 'Content',
                'code' => 'content',
                'route' => null,
                'order' => 3,
                'icon' => 'heroicon-o-document-text',
                'status' => 'active',
                'show_in_menu' => true,
                'children' => [
                    ['name' => 'News Articles', 'code' => 'news_articles', 'route' => "/{$filamentPath}/news-articles", 'order' => 1, 'icon' => 'heroicon-o-newspaper'],
                    ['name' => 'Page Contents', 'code' => 'page_contents', 'route' => "/{$filamentPath}/page-contents", 'order' => 2, 'icon' => 'heroicon-o-document-text'],
                    ['name' => 'Sliders', 'code' => 'sliders', 'route' => "/{$filamentPath}/sliders", 'order' => 3, 'icon' => 'heroicon-o-photo'],
                    ['name' => 'Reviews', 'code' => 'reviews', 'route' => "/{$filamentPath}/reviews", 'order' => 4, 'icon' => 'heroicon-o-star'],
                    ['name' => 'User Comments', 'code' => 'user_comments', 'route' => "/{$filamentPath}/user-comments", 'order' => 5, 'icon' => 'heroicon-o-chat-bubble-left-right'],
                    ['name' => 'Blog Posts', 'code' => 'blog_posts', 'route' => "/{$filamentPath}/blog-posts", 'order' => 6, 'icon' => 'heroicon-o-pencil-square'],
                    ['name' => 'Site Popups', 'code' => 'popups', 'route' => "/{$filamentPath}/popups", 'order' => 7, 'icon' => 'heroicon-o-megaphone'],
                    ['name' => 'Services', 'code' => 'services', 'route' => "/{$filamentPath}/services", 'order' => 8, 'icon' => 'heroicon-o-briefcase'],
                    ['name' => 'Team Members', 'code' => 'team_members', 'route' => "/{$filamentPath}/team-members", 'order' => 9, 'icon' => 'heroicon-o-user-group'],
                    ['name' => 'Header Menu', 'code' => 'menu_items', 'route' => "/{$filamentPath}/menu-items", 'order' => 10, 'icon' => 'heroicon-o-bars-3'],
                ],
            ],
            [
                'name' => 'Tech Lab & Tools',
                'code' => 'tech_lab_and_tools',
                'route' => null,
                'order' => 4,
                'icon' => 'heroicon-o-wrench-screwdriver',
                'status' => 'active',
                'show_in_menu' => true,
                'children' => [
                    ['name' => 'Tech Guides', 'code' => 'tech_guides', 'route' => "/{$filamentPath}/tech-guides", 'order' => 1, 'icon' => 'heroicon-o-book-open'],
                    ['name' => 'Camera Shootouts', 'code' => 'camera_shootouts', 'route' => "/{$filamentPath}/camera-shootouts", 'order' => 2, 'icon' => 'heroicon-o-camera'],
                    ['name' => 'Rival Matchups', 'code' => 'rival_matchups', 'route' => "/{$filamentPath}/rival-matchups", 'order' => 3, 'icon' => 'heroicon-o-scale'],
                    ['name' => 'Carrier Bands', 'code' => 'carrier_frequency_bands', 'route' => "/{$filamentPath}/carrier-frequency-bands", 'order' => 4, 'icon' => 'heroicon-o-signal'],
                    ['name' => 'Service Centers', 'code' => 'service_centers', 'route' => "/{$filamentPath}/authorized-service-centers", 'order' => 5, 'icon' => 'heroicon-o-wrench-screwdriver'],
                    ['name' => 'Bank Partners', 'code' => 'bank_partners', 'route' => "/{$filamentPath}/bank-partners", 'order' => 6, 'icon' => 'heroicon-o-credit-card'],
                    ['name' => 'Upcoming Launches', 'code' => 'upcoming_launches', 'route' => "/{$filamentPath}/upcoming-launches", 'order' => 7, 'icon' => 'heroicon-o-rocket-launch'],
                ],
            ],
            [
                'name' => 'Sales',
                'code' => 'sales',
                'route' => null,
                'order' => 5,
                'icon' => 'heroicon-o-shopping-bag',
                'status' => 'active',
                'show_in_menu' => true,
                'children' => [
                    ['name' => 'Orders', 'code' => 'orders', 'route' => "/{$filamentPath}/orders", 'order' => 1, 'icon' => 'heroicon-o-shopping-bag'],
                ],
            ],
            [
                'name' => 'Users',
                'code' => 'users',
                'route' => null,
                'order' => 6,
                'icon' => 'heroicon-o-users',
                'status' => 'active',
                'show_in_menu' => true,
                'children' => [
                    ['name' => 'Users List', 'code' => 'users_list', 'route' => "/{$filamentPath}/users", 'order' => 1, 'icon' => 'heroicon-o-users'],
                    ['name' => 'Roles & Permissions', 'code' => 'roles', 'route' => "/{$filamentPath}/roles", 'order' => 2, 'icon' => 'heroicon-o-shield-check'],
                    ['name' => 'Contact Messages', 'code' => 'contact_messages', 'route' => "/{$filamentPath}/contact-messages", 'order' => 3, 'icon' => 'heroicon-o-envelope'],
                ],
            ],
            [
                'name' => 'Settings',
                'code' => 'settings',
                'route' => null,
                'order' => 7,
                'icon' => 'heroicon-o-cog-6-tooth',
                'status' => 'active',
                'show_in_menu' => true,
                'children' => [
                    ['name' => 'Contact & Social Links', 'code' => 'contact_social', 'route' => "/{$filamentPath}/contact-and-social-settings", 'order' => 0, 'icon' => 'heroicon-o-phone'],
                    ['name' => 'Site Settings', 'code' => 'site_settings', 'route' => "/{$filamentPath}/site-settings", 'order' => 1, 'icon' => 'heroicon-o-cog-6-tooth'],
                    ['name' => 'System Modules', 'code' => 'system_modules', 'route' => "/{$filamentPath}/system-modules", 'order' => 2, 'icon' => 'heroicon-o-squares-plus'],
                ],
            ],
            [
                'name' => 'SEO',
                'code' => 'seo',
                'route' => null,
                'order' => 8,
                'icon' => 'heroicon-o-magnifying-glass-circle',
                'status' => 'active',
                'show_in_menu' => true,
                'children' => [
                    ['name' => 'Global SEO Settings', 'code' => 'seo_settings', 'route' => "/{$filamentPath}/seo-settings", 'order' => 1, 'icon' => 'heroicon-o-adjustments-horizontal'],
                    ['name' => 'Page SEO', 'code' => 'seo_pages', 'route' => "/{$filamentPath}/seo-pages", 'order' => 2, 'icon' => 'heroicon-o-document-magnifying-glass'],
                    ['name' => 'URL Redirects', 'code' => 'seo_redirects', 'route' => "/{$filamentPath}/seo-redirects", 'order' => 3, 'icon' => 'heroicon-o-arrow-uturn-right'],
                ],
            ],
        ];

        foreach ($structure as $groupData) {
            $children = $groupData['children'];
            unset($groupData['children']);

            $parent = self::updateOrCreate(
                ['code' => $groupData['code']],
                $groupData
            );

            foreach ($children as $childData) {
                $childData['parent_id'] = $parent->id;
                $childData['status'] = 'active';
                $childData['show_in_menu'] = true;

                self::updateOrCreate(
                    ['code' => $childData['code']],
                    $childData
                );
            }
        }
    }
}
