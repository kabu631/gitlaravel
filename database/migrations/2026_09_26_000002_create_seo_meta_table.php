<?php

use App\Models\SystemModule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('seoable');
            $table->string('route_name')->nullable()->unique();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 500)->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots', 40)->nullable();
            $table->json('robots_directives')->nullable();

            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type', 40)->nullable();

            $table->string('twitter_card', 40)->nullable();
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();

            $table->longText('structured_data')->nullable();
            $table->timestamps();

            $table->unique(['seoable_type', 'seoable_id']);
        });

        // Carry over the meta descriptions that already exist on content tables.
        $now = now();
        foreach ([
            'blog_posts'     => \App\Models\BlogPost::class,
            'news_articles'  => \App\Models\NewsArticle::class,
            'page_contents'  => \App\Models\PageContent::class,
        ] as $table => $class) {
            DB::table($table)->whereNotNull('meta_description')->where('meta_description', '!=', '')
                ->orderBy('id')->each(function ($row) use ($class, $now) {
                    DB::table('seo_meta')->insert([
                        'seoable_type'     => $class,
                        'seoable_id'       => $row->id,
                        'meta_description' => $row->meta_description,
                        'created_at'       => $now,
                        'updated_at'       => $now,
                    ]);
                });
        }

        $defaults = [
            'seo_title_separator'     => '|',
            'seo_default_title'       => "Nepal's #1 Tech Review, Gadget Prices & Comparison",
            'seo_default_description' => "Nepal's trusted tech review & gadget price comparison platform. Discover smartphones, laptops, and accessories with honest reviews and the best prices.",
            'seo_default_keywords'    => 'gadgets nepal, mobile price in nepal, laptop price in nepal, tech reviews',
            'seo_twitter_site'        => '@gitinfosys',
            'seo_twitter_card'        => 'summary_large_image',
            'seo_robots_default'      => 'index, follow',
            'seo_discourage_indexing' => '0',
        ];
        foreach ($defaults as $key => $value) {
            DB::table('site_settings')->insertOrIgnore([
                'key' => $key, 'value' => $value, 'group' => 'seo', 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        if (Schema::hasTable('system_modules')) {
            $path = config('filament.path', 'secure-admin');
            $parent = SystemModule::firstOrCreate(['code' => 'seo'], [
                'name' => 'SEO', 'route' => null, 'order' => 8, 'icon' => 'heroicon-o-magnifying-glass-circle',
                'status' => 'active', 'show_in_menu' => true,
            ]);
            SystemModule::firstOrCreate(['code' => 'seo_settings'], [
                'name' => 'Global SEO Settings', 'route' => "/{$path}/seo-settings",
                'order' => 1, 'icon' => 'heroicon-o-adjustments-horizontal',
                'status' => 'active', 'show_in_menu' => true, 'parent_id' => $parent->id,
            ]);
            SystemModule::firstOrCreate(['code' => 'seo_pages'], [
                'name' => 'Page SEO', 'route' => "/{$path}/seo-pages",
                'order' => 2, 'icon' => 'heroicon-o-document-magnifying-glass',
                'status' => 'active', 'show_in_menu' => true, 'parent_id' => $parent->id,
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('system_modules')) {
            SystemModule::whereIn('code', ['seo_settings', 'seo_pages', 'seo'])->delete();
        }
        DB::table('site_settings')->where('group', 'seo')->delete();
        Schema::dropIfExists('seo_meta');
    }
};
