<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('upcoming_launches', function (Blueprint $table) {
            $table->string('category')->nullable()->default('Smartphones')->change();
            $table->string('est_price')->nullable()->default('TBA')->change();
        });
    }

    public function down(): void
    {
        Schema::table('upcoming_launches', function (Blueprint $table) {
            $table->string('category')->nullable(false)->change();
            $table->string('est_price')->nullable(false)->change();
        });
    }
};
