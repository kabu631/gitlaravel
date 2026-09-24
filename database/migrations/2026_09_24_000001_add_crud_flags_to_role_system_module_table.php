<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('role_system_module', function (Blueprint $table) {
            $table->boolean('can_view')->default(false)->after('system_module_id');
            $table->boolean('can_create')->default(false)->after('can_view');
            $table->boolean('can_update')->default(false)->after('can_create');
            $table->boolean('can_delete')->default(false)->after('can_update');
        });

        // Existing grants were full access; keep that behaviour.
        DB::table('role_system_module')->update([
            'can_view' => true,
            'can_create' => true,
            'can_update' => true,
            'can_delete' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('role_system_module', function (Blueprint $table) {
            $table->dropColumn(['can_view', 'can_create', 'can_update', 'can_delete']);
        });
    }
};
