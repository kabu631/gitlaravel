<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadget_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('content');
            $table->decimal('rating', 3, 1);
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->text('verdict')->nullable();
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('react_happy')->default(0);
            $table->unsignedInteger('react_sad')->default(0);
            $table->unsignedInteger('react_love')->default(0);
            $table->unsignedInteger('react_like')->default(0);
            $table->unsignedInteger('react_funny')->default(0);
            $table->unsignedInteger('react_angry')->default(0);
            $table->timestamps();
        });

        Schema::create('user_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadget_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment');
            $table->timestamps();
            $table->unique(['gadget_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_comments');
        Schema::dropIfExists('reviews');
    }
};
