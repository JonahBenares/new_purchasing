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
        Schema::create('po_details', function (Blueprint $table) {
            $table->id();
            $table->integer('po_head_id')->default(0);
            $table->integer('item_no')->default(0);
            $table->integer('pr_details_id')->default(0);
            $table->integer('rfq_offers_id')->default(0);
            $table->integer('reference_po_details_id')->default(0);
            $table->string('reference_po_no')->nullable();
            $table->text('item_description')->nullable();
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
        Schema::dropIfExists('po_details');
    }
};
