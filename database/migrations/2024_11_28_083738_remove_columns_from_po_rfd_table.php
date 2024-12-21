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
        Schema::table('po_rfd', function (Blueprint $table) {
            $table->dropColumn('ewt_perc');
            $table->dropColumn('ewt_amount');
            $table->dropColumn('ewt');
            $table->dropColumn('vat');
            $table->dropColumn('vat_amount');
            $table->dropColumn('payment_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('po_rfd', function (Blueprint $table) {
            $table->double('ewt_perc');
            $table->double('ewt_amount');
            $table->integer('ewt');
            $table->integer('vat');
            $table->double('vat_amount');
            $table->double('payment_amount');
        });
    }
};
