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
            $table->text('item_description')->nullable()->after('rfq_offers_id');
            $table->string('uom')->nullable()->after('item_description');
            $table->double('unit_price')->default(0)->after('quantity');
            $table->string('currency')->nullable()->after('unit_price');
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
