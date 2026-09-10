<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tech_guides', function (Blueprint $table) {
            $table->string('type', 50)->nullable()->after('slug')->comment('null=general, buying-guide, how-to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tech_guides', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
