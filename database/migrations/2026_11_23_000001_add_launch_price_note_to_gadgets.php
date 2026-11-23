<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * For devices with no current Nepal price (discontinued models, consoles and games that are only
 * grey-imported): the original launch price, shown clearly labelled instead of "Rs 0".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gadgets', function (Blueprint $table) {
            $table->string('launch_price_note', 120)->nullable()->after('price_is_estimate');
        });
    }

    public function down(): void
    {
        Schema::table('gadgets', function (Blueprint $table) {
            $table->dropColumn('launch_price_note');
        });
    }
};
