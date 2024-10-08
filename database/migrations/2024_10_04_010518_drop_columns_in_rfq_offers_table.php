<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rfq_offers', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('canvassed_by');
            $table->dropColumn('canvassed_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rfq_offers', function (Blueprint $table) {
            //
        });
    }
};
