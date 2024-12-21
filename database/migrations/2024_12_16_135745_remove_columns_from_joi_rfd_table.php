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
        Schema::table('joi_rfd', function (Blueprint $table) {
            $table->dropColumn('sub_total');
            $table->dropColumn('ewt_perc');
            $table->dropColumn('ewt_amount');
            $table->dropColumn('ewt');
            $table->dropColumn('vat');
            $table->dropColumn('vat_amount');
            $table->dropColumn('payment_amount');
            $table->dropColumn('payment_description');
            $table->dropColumn('retention_perc');
            $table->dropColumn('retention_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joi_rfd', function (Blueprint $table) {
            $table->double('sub_total');
            $table->double('ewt_perc');
            $table->double('ewt_amount');
            $table->double('ewt');
            $table->double('vat');
            $table->double('vat_amount');
            $table->double('payment_amount');
            $table->double('payment_description');
            $table->double('retention_perc');
            $table->double('retention_amount');
        });
    }
};
