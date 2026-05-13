<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gadget_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadget_id')->constrained()->cascadeOnDelete();
            $table->string('variant_type', 20); // ram, color, storage, screen_size, connectivity, other
            $table->string('value', 100);
            $table->decimal('price', 12, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gadget_variants');
    }
};
