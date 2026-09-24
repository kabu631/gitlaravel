<?php

use App\Models\SystemModule;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        $parent = SystemModule::where('code', 'settings')->first();
        if (! $parent) {
            return;
        }

        $path = config('filament.path', 'secure-admin');

        SystemModule::firstOrCreate(['code' => 'contact_social'], [
            'name' => 'Contact & Social Links', 'route' => "/{$path}/contact-and-social-settings",
            'order' => 0, 'icon' => 'heroicon-o-phone',
            'status' => 'active', 'show_in_menu' => true, 'parent_id' => $parent->id,
        ]);
    }

    public function down(): void
    {
        SystemModule::where('code', 'contact_social')->delete();
    }
};
