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
        Schema::create('system_modules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Menu Title
            $table->string('code')->unique(); // Module Code
            $table->string('route')->nullable(); // Menu Link (Route)
            $table->integer('order')->default(1); // Menu Order
            $table->string('icon')->nullable(); // Menu Icon
            $table->string('status')->default('active'); // Module Status ('active', 'inactive')
            $table->foreignId('parent_id')->nullable()->constrained('system_modules')->nullOnDelete(); // Parent Menu
            $table->foreignId('sub_parent_id')->nullable()->constrained('system_modules')->nullOnDelete(); // Parent Sub-menu
            $table->boolean('show_in_menu')->default(true); // Show in Navigation Menu
            $table->timestamps();

            $table->index(['status', 'show_in_menu', 'order']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_modules');
    }
};
