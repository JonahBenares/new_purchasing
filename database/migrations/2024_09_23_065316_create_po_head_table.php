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
        Schema::create('po_head', function (Blueprint $table) {
            $table->id();
            $table->string('pr_no')->nullable();
            $table->integer('vendor_details_id')->default(0);
            $table->string('vendor_name')->nullable();
            $table->string('po_no')->nullable();
            $table->string('po_date')->nullable();
            $table->double('shipping_cost')->default(0);
            $table->double('handling_fee')->default(0);
            $table->double('discount')->default(0);
            $table->integer('vat')->default(0);
            $table->double('vat_amount')->default(0);
            $table->double('grand_total')->default(0);
            $table->integer('prepared_by')->default(0);
            $table->integer('checked_by')->default(0);
            $table->integer('recommended_by')->default(0);
            $table->integer('approved_by')->default(0);
            $table->integer('user_id')->default(0);
            $table->string('revision_no')->nullable();
            $table->string('method')->comment('PO,DPO,RPO')->nullable();
            $table->string('status')->comment('draft,saved,cancelled')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_head');
    }
};
