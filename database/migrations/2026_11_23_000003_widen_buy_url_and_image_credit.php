<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Some retailer product URLs (e.g. ONIN Infosys) run past 255 characters.
 * Match gadget_images.source_url, which is already 500.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gadgets', fn (Blueprint $table) => $table->string('buy_url', 500)->nullable()->change());
        Schema::table('gadget_images', fn (Blueprint $table) => $table->string('credit', 500)->nullable()->change());
    }

    public function down(): void
    {
        Schema::table('gadgets', fn (Blueprint $table) => $table->string('buy_url', 255)->nullable()->change());
        Schema::table('gadget_images', fn (Blueprint $table) => $table->string('credit', 255)->nullable()->change());
    }
};
