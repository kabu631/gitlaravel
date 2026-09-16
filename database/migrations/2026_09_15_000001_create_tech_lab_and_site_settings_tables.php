<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Blind Camera Shootout Arena
        Schema::create('camera_shootouts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->default('Camera');
            
            // Mystery Device Alpha (Phone A)
            $table->string('device_a_name');
            $table->string('device_a_specs')->nullable();
            $table->string('device_a_exif')->nullable();
            $table->string('device_a_image'); // Sample shot
            $table->string('device_a_device_image')->nullable(); // Real hardware photo
            $table->unsignedInteger('phone_a_votes')->default(0);

            // Mystery Device Beta (Phone B)
            $table->string('device_b_name');
            $table->string('device_b_specs')->nullable();
            $table->string('device_b_exif')->nullable();
            $table->string('device_b_image'); // Sample shot
            $table->string('device_b_device_image')->nullable(); // Real hardware photo
            $table->unsignedInteger('phone_b_votes')->default(0);

            // Lab & Editorial Analysis
            $table->string('winner_summary')->nullable();
            $table->text('editorial_deep_dive')->nullable();

            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 2. Partner Commercial Banks for 0% EMI Installment Schemes
        Schema::create('bank_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->json('supported_tenures')->nullable(); // e.g. [3, 6, 9, 12, 18]
            $table->decimal('processing_fee_percent', 5, 2)->default(0.00);
            $table->decimal('min_downpayment_percent', 5, 2)->default(0.00);
            $table->text('terms_note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 3. Nepal 5G & 4G Carrier Frequency Bands
        Schema::create('carrier_frequency_bands', function (Blueprint $table) {
            $table->id();
            $table->string('carrier'); // 'ntc' or 'ncell'
            $table->string('code'); // e.g. 'Band n78 (3500MHz)'
            $table->string('technology'); // e.g. '5G Sub-6', '4G LTE FDD'
            $table->string('frequency'); // e.g. '3500 MHz (C-Band)'
            $table->text('role')->nullable();
            $table->string('status_badge')->default('Commercial'); // 'Trial Live', 'Commercial Primary'
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 4. Authorized Service & Repair Centers Directory
        Schema::create('authorized_service_centers', function (Blueprint $table) {
            $table->id();
            $table->string('brand'); // 'Apple', 'Samsung', 'Xiaomi', 'OnePlus', etc.
            $table->string('name');
            $table->string('address');
            $table->string('city')->default('Kathmandu');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('avg_screen_cost')->nullable(); // e.g. 'Rs. 28,000 - 42,000 (OLED)'
            $table->boolean('is_authorized')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 5. Curated Flagship Rival Matchups (Battleground Arena)
        Schema::create('rival_matchups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('category_slug'); // 'mobile', 'laptop', 'earbuds', 'smartwatch'
            $table->foreignId('device_a_id')->nullable()->constrained('gadgets')->nullOnDelete();
            $table->foreignId('device_b_id')->nullable()->constrained('gadgets')->nullOnDelete();
            $table->json('metrics')->nullable(); // Array of [{label, left_val, right_val, left_pct, right_pct}]
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 6. Global Site Settings (Key-Value configuration)
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('group')->default('general'); // 'announcement', 'contact', 'social', 'cookie', 'mdms'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('rival_matchups');
        Schema::dropIfExists('authorized_service_centers');
        Schema::dropIfExists('carrier_frequency_bands');
        Schema::dropIfExists('bank_partners');
        Schema::dropIfExists('camera_shootouts');
    }
};
