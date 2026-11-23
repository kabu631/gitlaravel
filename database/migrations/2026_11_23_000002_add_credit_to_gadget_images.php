<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Visible attribution for images that need it (e.g. Wikimedia Commons photos under CC BY-SA):
     * "Photo: Author, CC BY-SA 4.0, via Wikimedia Commons". Official manufacturer images leave it empty.
     */
    public function up(): void
    {
        Schema::table('gadget_images', function (Blueprint $table) {
            $table->string('credit', 255)->nullable()->after('source_url');
        });
    }

    public function down(): void
    {
        Schema::table('gadget_images', function (Blueprint $table) {
            $table->dropColumn('credit');
        });
    }
};
