<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('content');
            $table->enum('category', ['tech', 'mobile', 'laptop', 'gaming', 'ai', 'software', 'gadgets', 'telecom'])->default('tech');
            $table->string('thumbnail')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('views_count')->default(0);
            $table->string('meta_description', 160)->nullable();
            $table->unsignedInteger('react_happy')->default(0);
            $table->unsignedInteger('react_sad')->default(0);
            $table->unsignedInteger('react_love')->default(0);
            $table->unsignedInteger('react_like')->default(0);
            $table->unsignedInteger('react_funny')->default(0);
            $table->unsignedInteger('react_angry')->default(0);
            $table->timestamps();
        });

        Schema::create('tech_guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('content');
            $table->string('thumbnail')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tech_guides');
        Schema::dropIfExists('news_articles');
    }
};
