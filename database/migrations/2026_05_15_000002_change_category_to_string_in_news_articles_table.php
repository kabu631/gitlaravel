<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Modify category column in news_articles to VARCHAR(50) so any category like gpu, price-trends, sci-fi is accepted
        DB::statement("ALTER TABLE `news_articles` MODIFY COLUMN `category` VARCHAR(50) NOT NULL DEFAULT 'tech'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `news_articles` MODIFY COLUMN `category` ENUM('tech', 'mobile', 'laptop', 'gaming', 'ai', 'software', 'gadgets', 'telecom') NOT NULL DEFAULT 'tech'");
    }
};
