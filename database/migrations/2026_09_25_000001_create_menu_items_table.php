<?php

use App\Models\MenuItem;
use App\Models\SystemModule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $t->string('label');
            $t->string('type', 20)->default('link');
            $t->string('url', 500)->nullable();
            $t->string('icon', 40)->nullable();
            $t->string('subtitle')->nullable();
            $t->string('badge_text', 20)->nullable();
            $t->string('style', 20)->default('default');
            $t->boolean('open_in_new_tab')->default(false);
            $t->boolean('show_on_mobile')->default(true);
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        MenuItem::seedDefaults();

        $parent = SystemModule::where('code', 'content')->first();
        if ($parent) {
            $path = config('filament.path', 'secure-admin');
            SystemModule::firstOrCreate(['code' => 'menu_items'], [
                'name' => 'Header Menu', 'route' => "/{$path}/menu-items", 'order' => 10,
                'icon' => 'heroicon-o-bars-3', 'status' => 'active', 'show_in_menu' => true,
                'parent_id' => $parent->id,
            ]);
        }
    }

    public function down(): void
    {
        SystemModule::where('code', 'menu_items')->delete();
        Schema::dropIfExists('menu_items');
    }
};
