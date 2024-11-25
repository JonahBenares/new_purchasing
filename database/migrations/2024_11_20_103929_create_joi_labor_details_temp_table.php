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
        Schema::create('joi_labor_details_temp', function (Blueprint $table) {
            $table->id();
            $table->integer('joi_labor_details_id')->default(0);
            $table->integer('joi_head_id')->default(0);
            $table->integer('jor_labor_details_id')->default(0);
            $table->integer('jo_rfq_labor_offer_id')->default(0);
            $table->string('item_description')->nullable();
            $table->double('quantity')->default(0);
            $table->string('uom')->nullable();
            $table->double('unit_price')->default(0);
            $table->double('total_cost')->default(0);
            $table->string('currency')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joi_labor_details_temp');
    }
};
