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
        Schema::create('joi_material_details', function (Blueprint $table) {
            $table->id();
            $table->integer('joi_head_id')->default(0);
            $table->integer('item_no')->default(0);
            $table->integer('jor_material_details_id')->default(0);
            $table->text('item_description')->nullable();
            $table->integer('jo_rfq_material_offer_id')->default(0);
            $table->text('offer')->nullable();
            $table->double('quantity')->default(0);
            $table->string('uom')->nullable();
            $table->double('unit_price')->default(0);
            $table->double('total_cost')->default(0);
            $table->string('currency')->nullable();
            $table->string('reference_joi_no')->nullable();
            $table->integer('reference_joi_material_details_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joi_material_details');
    }
};
