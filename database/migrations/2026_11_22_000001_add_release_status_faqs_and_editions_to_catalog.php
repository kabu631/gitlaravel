<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catalog content upgrade:
 *  - gadgets can be "announced" (launched globally, Nepal price not official yet) or "upcoming"
 *    (not launched), with an expected-launch label and an estimated-price flag;
 *  - FAQs (rendered on the product page and as FAQPage structured data) and research sources;
 *  - an "edition" dimension on SKU variants (e.g. PS5 Disc / Digital, game Standard / Ultimate);
 *  - upcoming-launch entries can point at a full product page;
 *  - gallery images keep a credit/source URL for licensing records.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gadgets', function (Blueprint $table) {
            $table->string('release_status', 20)->default('available')->after('release_date')->index();
            $table->string('expected_launch', 120)->nullable()->after('release_status');
            $table->boolean('price_is_estimate')->default(false)->after('old_price');
            $table->json('faqs')->nullable()->after('description');
            $table->json('sources')->nullable()->after('faqs');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('edition', 100)->nullable()->after('storage');
        });

        Schema::table('upcoming_launches', function (Blueprint $table) {
            $table->foreignId('gadget_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });

        Schema::table('gadget_images', function (Blueprint $table) {
            $table->string('source_url', 500)->nullable()->after('alt_text');
        });
    }

    public function down(): void
    {
        Schema::table('gadget_images', fn (Blueprint $table) => $table->dropColumn('source_url'));

        Schema::table('upcoming_launches', function (Blueprint $table) {
            $table->dropConstrainedForeignId('gadget_id');
        });

        Schema::table('product_variants', fn (Blueprint $table) => $table->dropColumn('edition'));

        Schema::table('gadgets', function (Blueprint $table) {
            $table->dropIndex(['release_status']);
            $table->dropColumn(['release_status', 'expected_launch', 'price_is_estimate', 'faqs', 'sources']);
        });
    }
};
