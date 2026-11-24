<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Date the retailer was last seen out of stock. Null = in stock, or not known to be out of stock. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gadgets', fn (Blueprint $table) => $table->date('out_of_stock_at')->nullable()->after('buy_url'));
    }

    public function down(): void
    {
        Schema::table('gadgets', fn (Blueprint $table) => $table->dropColumn('out_of_stock_at'));
    }
};
