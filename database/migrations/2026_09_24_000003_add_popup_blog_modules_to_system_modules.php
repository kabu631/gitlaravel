<?php

use App\Models\SystemModule;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        $path   = config('filament.path', 'secure-admin');
        $parent = SystemModule::where('code', 'content')->first();
        if (! $parent) {
            return;
        }

        $items = [
            ['Blog Posts', 'blog_posts', 'blog-posts', 6, 'heroicon-o-pencil-square'],
            ['Site Popups', 'popups', 'popups', 7, 'heroicon-o-megaphone'],
            ['Services', 'services', 'services', 8, 'heroicon-o-briefcase'],
            ['Team Members', 'team_members', 'team-members', 9, 'heroicon-o-user-group'],
        ];

        foreach ($items as [$name, $code, $slug, $order, $icon]) {
            SystemModule::firstOrCreate(['code' => $code], [
                'name' => $name, 'route' => "/{$path}/{$slug}", 'order' => $order, 'icon' => $icon,
                'status' => 'active', 'show_in_menu' => true, 'parent_id' => $parent->id,
            ]);
        }
    }

    public function down(): void
    {
        SystemModule::whereIn('code', ['blog_posts', 'popups', 'services', 'team_members'])->delete();
    }
};
