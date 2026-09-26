<?php

use App\Models\SystemModule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('seo_meta', function (Blueprint $table) {
            $table->string('image_alt')->nullable()->after('og_type');
        });

        Schema::create('seo_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_path')->unique();
            $table->string('to_path');
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->timestamps();
        });

        if (Schema::hasTable('system_modules') && $parent = SystemModule::where('code', 'seo')->first()) {
            $path = config('filament.path', 'secure-admin');
            SystemModule::firstOrCreate(['code' => 'seo_redirects'], [
                'name' => 'URL Redirects', 'route' => "/{$path}/seo-redirects",
                'order' => 3, 'icon' => 'heroicon-o-arrow-uturn-right',
                'status' => 'active', 'show_in_menu' => true, 'parent_id' => $parent->id,
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('system_modules')) {
            SystemModule::where('code', 'seo_redirects')->delete();
        }
        Schema::dropIfExists('seo_redirects');
        Schema::table('seo_meta', fn (Blueprint $table) => $table->dropColumn('image_alt'));
    }
};
