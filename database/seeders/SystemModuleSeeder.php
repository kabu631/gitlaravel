<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\SystemModule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SystemModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Schema::hasTable('system_modules')) {
            $this->command?->warn('system_modules table does not exist. Please run migrations first.');
            return;
        }

        $filamentPath = config('filament.path', 'secure-admin');

        $modules = [
            // ── 1. Dashboard ────────────────────────────────────────────────
            [
                'name'         => 'Dashboard',
                'code'         => 'dashboard',
                'route'        => "/{$filamentPath}",
                'order'        => 1,
                'icon'         => 'heroicon-o-home',
                'status'       => 'active',
                'show_in_menu' => true,
                'children'     => [],
            ],

            // ── 2. Catalog ──────────────────────────────────────────────────
            [
                'name'         => 'Catalog',
                'code'         => 'catalog',
                'route'        => null,
                'order'        => 2,
                'icon'         => 'heroicon-o-tag',
                'status'       => 'active',
                'show_in_menu' => true,
                'children'     => [
                    [
                        'name'         => 'Gadgets',
                        'code'         => 'gadgets',
                        'route'        => "/{$filamentPath}/gadgets",
                        'order'        => 1,
                        'icon'         => 'heroicon-o-device-phone-mobile',
                    ],
                    [
                        'name'         => 'Product Variants',
                        'code'         => 'product_variants',
                        'route'        => "/{$filamentPath}/product-variants",
                        'order'        => 2,
                        'icon'         => 'heroicon-o-squares-2x2',
                    ],
                    [
                        'name'         => 'Brands',
                        'code'         => 'brands',
                        'route'        => "/{$filamentPath}/brands",
                        'order'        => 3,
                        'icon'         => 'heroicon-o-building-office',
                    ],
                    [
                        'name'         => 'Categories',
                        'code'         => 'categories',
                        'route'        => "/{$filamentPath}/categories",
                        'order'        => 4,
                        'icon'         => 'heroicon-o-folder',
                    ],
                    [
                        'name'         => 'Accessory Types',
                        'code'         => 'accessory_types',
                        'route'        => "/{$filamentPath}/accessory-types",
                        'order'        => 5,
                        'icon'         => 'heroicon-o-tag',
                    ],
                ],
            ],

            // ── 3. Content ──────────────────────────────────────────────────
            [
                'name'         => 'Content',
                'code'         => 'content',
                'route'        => null,
                'order'        => 3,
                'icon'         => 'heroicon-o-document-text',
                'status'       => 'active',
                'show_in_menu' => true,
                'children'     => [
                    [
                        'name'         => 'News Articles',
                        'code'         => 'news_articles',
                        'route'        => "/{$filamentPath}/news-articles",
                        'order'        => 1,
                        'icon'         => 'heroicon-o-newspaper',
                    ],
                    [
                        'name'         => 'Page Contents',
                        'code'         => 'page_contents',
                        'route'        => "/{$filamentPath}/page-contents",
                        'order'        => 2,
                        'icon'         => 'heroicon-o-document-text',
                    ],
                    [
                        'name'         => 'Sliders',
                        'code'         => 'sliders',
                        'route'        => "/{$filamentPath}/sliders",
                        'order'        => 3,
                        'icon'         => 'heroicon-o-photo',
                    ],
                    [
                        'name'         => 'Reviews',
                        'code'         => 'reviews',
                        'route'        => "/{$filamentPath}/reviews",
                        'order'        => 4,
                        'icon'         => 'heroicon-o-star',
                    ],
                    [
                        'name'         => 'User Comments',
                        'code'         => 'user_comments',
                        'route'        => "/{$filamentPath}/user-comments",
                        'order'        => 5,
                        'icon'         => 'heroicon-o-chat-bubble-left-right',
                    ],
                    [
                        'name'         => 'Blog Posts',
                        'code'         => 'blog_posts',
                        'route'        => "/{$filamentPath}/blog-posts",
                        'order'        => 6,
                        'icon'         => 'heroicon-o-pencil-square',
                    ],
                    [
                        'name'         => 'Site Popups',
                        'code'         => 'popups',
                        'route'        => "/{$filamentPath}/popups",
                        'order'        => 7,
                        'icon'         => 'heroicon-o-megaphone',
                    ],
                    [
                        'name'         => 'Services',
                        'code'         => 'services',
                        'route'        => "/{$filamentPath}/services",
                        'order'        => 8,
                        'icon'         => 'heroicon-o-briefcase',
                    ],
                    [
                        'name'         => 'Team Members',
                        'code'         => 'team_members',
                        'route'        => "/{$filamentPath}/team-members",
                        'order'        => 9,
                        'icon'         => 'heroicon-o-user-group',
                    ],
                    [
                        'name'         => 'Header Menu',
                        'code'         => 'menu_items',
                        'route'        => "/{$filamentPath}/menu-items",
                        'order'        => 10,
                        'icon'         => 'heroicon-o-bars-3',
                    ],
                    [
                        'name'         => 'Newsletter Subscribers',
                        'code'         => 'newsletter_subscribers',
                        'route'        => "/{$filamentPath}/newsletter-subscribers",
                        'order'        => 11,
                        'icon'         => 'heroicon-o-envelope',
                    ],
                    [
                        'name'         => 'Job Openings',
                        'code'         => 'job_openings',
                        'route'        => "/{$filamentPath}/job-openings",
                        'order'        => 12,
                        'icon'         => 'heroicon-o-briefcase',
                    ],
                ],
            ],

            // ── 4. Tech Lab & Tools ─────────────────────────────────────────
            [
                'name'         => 'Tech Lab & Tools',
                'code'         => 'tech_lab_and_tools',
                'route'        => null,
                'order'        => 4,
                'icon'         => 'heroicon-o-wrench-screwdriver',
                'status'       => 'active',
                'show_in_menu' => true,
                'children'     => [
                    [
                        'name'         => 'Tech Guides',
                        'code'         => 'tech_guides',
                        'route'        => "/{$filamentPath}/tech-guides",
                        'order'        => 1,
                        'icon'         => 'heroicon-o-book-open',
                    ],
                    [
                        'name'         => 'Camera Shootouts',
                        'code'         => 'camera_shootouts',
                        'route'        => "/{$filamentPath}/camera-shootouts",
                        'order'        => 2,
                        'icon'         => 'heroicon-o-camera',
                    ],
                    [
                        'name'         => 'Rival Matchups',
                        'code'         => 'rival_matchups',
                        'route'        => "/{$filamentPath}/rival-matchups",
                        'order'        => 3,
                        'icon'         => 'heroicon-o-scale',
                    ],
                    [
                        'name'         => 'Carrier Bands',
                        'code'         => 'carrier_frequency_bands',
                        'route'        => "/{$filamentPath}/carrier-frequency-bands",
                        'order'        => 4,
                        'icon'         => 'heroicon-o-signal',
                    ],
                    [
                        'name'         => 'Service Centers',
                        'code'         => 'service_centers',
                        'route'        => "/{$filamentPath}/authorized-service-centers",
                        'order'        => 5,
                        'icon'         => 'heroicon-o-wrench-screwdriver',
                    ],
                    [
                        'name'         => 'EMI Banks',
                        'code'         => 'bank_partners',
                        'route'        => "/{$filamentPath}/bank-partners",
                        'order'        => 6,
                        'icon'         => 'heroicon-o-credit-card',
                    ],
                    [
                        'name'         => 'Upcoming Launches',
                        'code'         => 'upcoming_launches',
                        'route'        => "/{$filamentPath}/upcoming-launches",
                        'order'        => 7,
                        'icon'         => 'heroicon-o-rocket-launch',
                    ],
                ],
            ],

            // ── 5. Sales ────────────────────────────────────────────────────
            [
                'name'         => 'Sales',
                'code'         => 'sales',
                'route'        => null,
                'order'        => 5,
                'icon'         => 'heroicon-o-shopping-bag',
                'status'       => 'active',
                'show_in_menu' => true,
                'children'     => [
                    [
                        'name'         => 'Orders',
                        'code'         => 'orders',
                        'route'        => "/{$filamentPath}/orders",
                        'order'        => 1,
                        'icon'         => 'heroicon-o-shopping-bag',
                    ],
                ],
            ],

            // ── 6. Users ────────────────────────────────────────────────────
            [
                'name'         => 'Users',
                'code'         => 'users',
                'route'        => null,
                'order'        => 6,
                'icon'         => 'heroicon-o-users',
                'status'       => 'active',
                'show_in_menu' => true,
                'children'     => [
                    [
                        'name'         => 'Users List',
                        'code'         => 'users_list',
                        'route'        => "/{$filamentPath}/users",
                        'order'        => 1,
                        'icon'         => 'heroicon-o-users',
                    ],
                    [
                        'name'         => 'Roles & Permissions',
                        'code'         => 'roles',
                        'route'        => "/{$filamentPath}/roles",
                        'order'        => 2,
                        'icon'         => 'heroicon-o-shield-check',
                    ],
                    [
                        'name'         => 'Contact Messages',
                        'code'         => 'contact_messages',
                        'route'        => "/{$filamentPath}/contact-messages",
                        'order'        => 3,
                        'icon'         => 'heroicon-o-envelope',
                    ],
                ],
            ],

            // ── 7. Settings ─────────────────────────────────────────────────
            [
                'name'         => 'Settings',
                'code'         => 'settings',
                'route'        => null,
                'order'        => 7,
                'icon'         => 'heroicon-o-cog-6-tooth',
                'status'       => 'active',
                'show_in_menu' => true,
                'children'     => [
                    [
                        'name'         => 'Contact & Social Links',
                        'code'         => 'contact_social',
                        'route'        => "/{$filamentPath}/contact-and-social-settings",
                        'order'        => 1,
                        'icon'         => 'heroicon-o-phone',
                    ],
                    [
                        'name'         => 'Site Settings',
                        'code'         => 'site_settings',
                        'route'        => "/{$filamentPath}/site-settings",
                        'order'        => 2,
                        'icon'         => 'heroicon-o-cog-6-tooth',
                    ],
                    [
                        'name'         => 'System Modules',
                        'code'         => 'system_modules',
                        'route'        => "/{$filamentPath}/system-modules",
                        'order'        => 3,
                        'icon'         => 'heroicon-o-squares-plus',
                    ],
                ],
            ],

            // ── 8. SEO ──────────────────────────────────────────────────────
            [
                'name'         => 'SEO',
                'code'         => 'seo',
                'route'        => null,
                'order'        => 8,
                'icon'         => 'heroicon-o-magnifying-glass-circle',
                'status'       => 'active',
                'show_in_menu' => true,
                'children'     => [
                    [
                        'name'         => 'Global SEO Settings',
                        'code'         => 'seo_settings',
                        'route'        => "/{$filamentPath}/seo-settings",
                        'order'        => 1,
                        'icon'         => 'heroicon-o-adjustments-horizontal',
                    ],
                    [
                        'name'         => 'Page SEO',
                        'code'         => 'seo_pages',
                        'route'        => "/{$filamentPath}/seo-pages",
                        'order'        => 2,
                        'icon'         => 'heroicon-o-document-magnifying-glass',
                    ],
                    [
                        'name'         => 'URL Redirects',
                        'code'         => 'seo_redirects',
                        'route'        => "/{$filamentPath}/seo-redirects",
                        'order'        => 3,
                        'icon'         => 'heroicon-o-arrow-uturn-right',
                    ],
                ],
            ],
        ];

        foreach ($modules as $groupData) {
            $children = $groupData['children'] ?? [];
            unset($groupData['children']);

            $parent = SystemModule::updateOrCreate(
                ['code' => $groupData['code']],
                [
                    'name'         => $groupData['name'],
                    'route'        => $groupData['route'],
                    'order'        => $groupData['order'],
                    'icon'         => $groupData['icon'],
                    'status'       => $groupData['status'] ?? 'active',
                    'show_in_menu' => $groupData['show_in_menu'] ?? true,
                ]
            );

            foreach ($children as $childData) {
                SystemModule::updateOrCreate(
                    ['code' => $childData['code']],
                    [
                        'name'         => $childData['name'],
                        'route'        => $childData['route'],
                        'order'        => $childData['order'],
                        'icon'         => $childData['icon'],
                        'status'       => $childData['status'] ?? 'active',
                        'show_in_menu' => $childData['show_in_menu'] ?? true,
                        'parent_id'    => $parent->id,
                    ]
                );
            }
        }

        // Synchronize default roles and permissions if roles table exists
        if (Schema::hasTable('roles') && class_exists(Role::class)) {
            Role::seedDefaultRoles();
        }

        $this->command?->info('System modules seeded and synchronized successfully.');
    }
}
