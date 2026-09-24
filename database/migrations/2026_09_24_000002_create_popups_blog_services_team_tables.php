<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('image')->nullable();
            $table->string('badge')->nullable();
            $table->string('btn_text')->nullable();
            $table->string('btn_url')->nullable();
            $table->unsignedSmallInteger('delay_seconds')->default(2);
            $table->string('frequency')->default('once_per_session'); // always | once_per_session | once_per_day | once
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('cover_image')->nullable();
            $table->string('category')->default('general');
            $table->json('tags')->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->default('⭐');
            $table->string('title');
            $table->text('description');
            $table->string('gradient')->default('linear-gradient(135deg, #6c5ce7, #4834d4)');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed with the content that used to be hardcoded in the frontend.
        $now = now();
        $services = [
            ['⭐', 'In-Depth Tech Reviews', 'Honest, hands-on tests of the latest smartphones, laptops, and wearables. Our expert verdicts help you make confident buying decisions.', 'linear-gradient(135deg, #6c5ce7, #4834d4)'],
            ['↔️', 'Gadget Comparisons', 'Use our powerful comparison engine to put devices head-to-head. Analyze specs, benchmarks, and camera differences instantly.', 'linear-gradient(135deg, #1877F2, #0A66C2)'],
            ['📈', 'Price & Specs Tracking', 'Official Nepal MRP and market rates tracked regularly with historical data to know if you are getting a genuine deal.', 'linear-gradient(135deg, #F56040, #C13584)'],
            ['📰', 'Daily Tech News', 'Stay updated with blazing-fast coverage on the latest tech launches, leaks, and industry insights.', 'linear-gradient(135deg, #00b894, #00cec9)'],
            ['🤝', 'Authorized Retail Guidance', 'To preserve 100% editorial impartiality, we guide buyers to authorized distributors and certified retailers across Nepal for authentic hardware and official warranties.', 'linear-gradient(135deg, #ff9f43, #ff6b6b)'],
            ['📚', 'Expert Buying Guides', 'Confused about specs? Our easy-to-read tech guides break down complex jargon so you can pick the right hardware.', 'linear-gradient(135deg, #a29bfe, #6c5ce7)'],
        ];
        foreach ($services as $i => $s) {
            DB::table('services')->insert([
                'icon' => $s[0], 'title' => $s[1], 'description' => $s[2], 'gradient' => $s[3],
                'sort_order' => $i, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        DB::table('team_members')->insert([
            ['name' => 'Kabindra Koirala', 'role' => 'Founder & Editor-in-Chief', 'bio' => 'Tech enthusiast passionate about making gadget buying decisions easier for Nepali consumers.', 'sort_order' => 0, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Tech Team', 'role' => 'Review Specialists', 'bio' => 'Our team of specialists rigorously test every product before publishing honest, unbiased reviews.', 'sort_order' => 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('services');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('popups');
    }
};
