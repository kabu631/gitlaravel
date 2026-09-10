<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gadgets', function (Blueprint $table) {
            if (!Schema::hasColumn('gadgets', 'buy_url')) {
                $table->string('buy_url')->nullable()->after('price_tracker_description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gadgets', function (Blueprint $table) {
            if (Schema::hasColumn('gadgets', 'buy_url')) {
                $table->dropColumn('buy_url');
            }
        });
    }
};
