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
        Schema::table('po_details_temp', function (Blueprint $table) {
            $table->string('reference_po_no')->nullable()->after('rfq_offers_id');
            $table->integer('reference_po_details_id')->default(0)->after('rfq_offers_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('po_details_temp', function (Blueprint $table) {
            //
        });
    }
};
