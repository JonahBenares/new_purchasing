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
        Schema::table('joi_head', function (Blueprint $table) {
            $table->dropColumn('shipping_cost');
            $table->dropColumn('handling_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joi_head', function (Blueprint $table) {
            $table->double('shipping_cost');
            $table->double('handling_fee');
        });
    }
};
