<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('spec_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadget_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('display')->nullable();
            $table->string('processor')->nullable();
            $table->string('ram', 100)->nullable();
            $table->string('storage', 255)->nullable();
            $table->string('battery', 255)->nullable();
            $table->string('camera', 500)->nullable();
            $table->string('os', 100)->nullable();
            $table->string('connectivity', 500)->nullable();
            $table->string('weight', 100)->nullable();
            $table->string('dimensions', 255)->nullable();
            $table->json('extra_specs')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spec_sheets');
    }
};
