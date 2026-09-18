<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Widen category to a plain string so any category (gpu, price-trends, sci-fi, ...) is accepted.
        Schema::table('news_articles', function (Blueprint $table) {
            $table->string('category', 50)->default('tech')->change();
        });
    }

    public function down(): void
    {
        Schema::table('news_articles', function (Blueprint $table) {
            $table->enum('category', ['tech', 'mobile', 'laptop', 'gaming', 'ai', 'software', 'gadgets', 'telecom'])
                ->default('tech')
                ->change();
        });
    }
};
