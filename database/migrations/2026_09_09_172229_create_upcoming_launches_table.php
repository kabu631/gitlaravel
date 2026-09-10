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
        Schema::create('upcoming_launches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand');
            $table->string('category');             // e.g. Smartphones, Laptops, Audio
            $table->string('expected_date');         // e.g. "Expected Oct 2025"
            $table->string('est_price');             // e.g. "Rs. 2,19,999"
            $table->unsignedTinyInteger('confidence')->default(90); // 0-100
            $table->string('badge')->nullable();     // e.g. "High Anticipation"
            $table->text('highlight')->nullable();   // Key feature highlight
            $table->string('tag_color')->default('emerald'); // emerald, blue, purple, amber, rose
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upcoming_launches');
    }
};
