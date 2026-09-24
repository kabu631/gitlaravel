<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Role $role) {
            if ($role->is_system) {
                return false;
            }
        });
    }


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user')->withTimestamps();
    }

    public function systemModules(): BelongsToMany
    {
        return $this->belongsToMany(SystemModule::class, 'role_system_module')
            ->withPivot(['can_view', 'can_create', 'can_update', 'can_delete'])
            ->withTimestamps();
    }

    /**
     * Grant view/create/update/delete on the given module ids.
     */
    public function grantFullAccess(iterable $moduleIds): void
    {
        $flags = ['can_view' => true, 'can_create' => true, 'can_update' => true, 'can_delete' => true];
        $payload = [];
        foreach ($moduleIds as $id) {
            $payload[$id] = $flags;
        }

        $this->systemModules()->sync($payload);
    }

    /**
     * Sync per-module CRUD permissions. Modules with no action enabled are
     * detached, and any enabled action implies view access.
     */
    public function syncModulePermissions(array $rows): void
    {
        $payload = [];
        foreach ($rows as $row) {
            $create = (bool) ($row['can_create'] ?? false);
            $update = (bool) ($row['can_update'] ?? false);
            $delete = (bool) ($row['can_delete'] ?? false);
            $view = (bool) ($row['can_view'] ?? false) || $create || $update || $delete;

            if (! $view || blank($row['module_id'] ?? null)) {
                continue;
            }

            $payload[(int) $row['module_id']] = [
                'can_view' => true,
                'can_create' => $create,
                'can_update' => $update,
                'can_delete' => $delete,
            ];
        }

        $this->systemModules()->sync($payload);
    }

    public function hasModule(int|string|SystemModule $module): bool
    {
        if ($module instanceof SystemModule) {
            return $this->systemModules->contains('id', $module->id);
        }

        if (is_numeric($module)) {
            return $this->systemModules->contains('id', (int) $module);
        }

        return $this->systemModules->contains('code', $module);
    }

    public static function seedDefaultRoles(): void
    {
        // 1. Super Admin
        $superAdmin = self::updateOrCreate(
            ['slug' => 'super_admin'],
            [
                'name' => 'Super Administrator',
                'description' => 'Full and unrestricted access to all dashboard menus, modules, and system settings.',
                'is_system' => true,
            ]
        );

        $allModules = SystemModule::all();
        $superAdmin->grantFullAccess($allModules->pluck('id'));

        // Assign super_admin role to admin user if exists
        $adminUser = User::where('email', 'admin@gitinfosys.com')->first();
        if ($adminUser && ! $adminUser->roles->contains($superAdmin->id)) {
            $adminUser->roles()->attach($superAdmin->id);
        }

        // 2. Catalog Manager
        $catalogManager = self::updateOrCreate(
            ['slug' => 'catalog_manager'],
            [
                'name' => 'Catalog Manager',
                'description' => 'Manages gadgets, product variants, brands, categories, and accessory types.',
                'is_system' => false,
            ]
        );
        $catalogModules = SystemModule::whereIn('code', [
            'dashboard', 'catalog', 'gadgets', 'product_variants', 'brands', 'categories', 'accessory_types'
        ])->pluck('id');
        $catalogManager->grantFullAccess($catalogModules);

        // 3. Content Editor
        $contentEditor = self::updateOrCreate(
            ['slug' => 'content_editor'],
            [
                'name' => 'Content Editor',
                'description' => 'Manages news articles, static page contents, hero sliders, reviews, and user comments.',
                'is_system' => false,
            ]
        );
        $contentModules = SystemModule::whereIn('code', [
            'dashboard', 'content', 'news_articles', 'page_contents', 'sliders', 'reviews', 'user_comments'
        ])->pluck('id');
        $contentEditor->grantFullAccess($contentModules);

        // 4. Tech Lab Specialist
        $techLab = self::updateOrCreate(
            ['slug' => 'tech_lab_specialist'],
            [
                'name' => 'Tech Lab Specialist',
                'description' => 'Manages tech guides, camera shootouts, rival matchups, carrier bands, bank partners, and upcoming launches.',
                'is_system' => false,
            ]
        );
        $techModules = SystemModule::whereIn('code', [
            'dashboard', 'tech_lab_and_tools', 'tech_guides', 'camera_shootouts', 'rival_matchups',
            'carrier_frequency_bands', 'service_centers', 'bank_partners', 'upcoming_launches'
        ])->pluck('id');
        $techLab->grantFullAccess($techModules);

        // 5. Sales & Orders Manager
        $salesManager = self::updateOrCreate(
            ['slug' => 'sales_manager'],
            [
                'name' => 'Sales & Orders Manager',
                'description' => 'View, update, and manage customer orders and deliveries.',
                'is_system' => false,
            ]
        );
        $salesModules = SystemModule::whereIn('code', [
            'dashboard', 'sales', 'orders'
        ])->pluck('id');
        $salesManager->grantFullAccess($salesModules);
    }
}
