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
        Schema::create('pr_report_details', function (Blueprint $table) {
            $table->id();
            $table->string('pr_no')->nullable();
            $table->integer('pr_details_id')->default(0);
            $table->text('item_description')->nullable();
            $table->integer('rfq_offer_id')->default(0);
            $table->text('offer')->nullable();
            $table->double('pr_qty')->default(0);
            $table->double('delivered_qty')->default(0);
            $table->double('po_qty')->default(0);
            $table->double('dpo_qty')->default(0);
            $table->double('rpo_qty')->default(0);
            $table->string('uom')->nullable();
            $table->double('unit_price')->default(0);
            $table->string('currency')->nullable();
            $table->string('method')->nullable();
            $table->string('status')->nullable();
            $table->text('status_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_report_details');
    }
};
