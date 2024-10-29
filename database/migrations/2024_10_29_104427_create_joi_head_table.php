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
        Schema::create('joi_head', function (Blueprint $table) {
            $table->id();
            $table->string('jor_no')->nullable();
            $table->integer('vendor_details_id')->default(0);
            $table->string('vendor_name')->nullable();
            $table->string('joi_date')->nullable();
            $table->string('joi_no')->nullable();
            $table->double('shipping_cost')->default(0);
            $table->double('handling_fee')->default(0);
            $table->double('discount')->default(0);
            $table->integer('vat')->default(0);
            $table->double('vat_amount')->default(0);
            $table->double('grand_total')->default(0);
            $table->string('method')->comment('PO,DPO,RPO')->nullable();
            $table->string('revision_no')->nullable();
            $table->string('rev_approved_by')->nullable();
            $table->string('rev_approved_date')->nullable();
            $table->text('rev_approved_reason')->nullable();
            $table->integer('prepared_by')->default(0);
            $table->integer('checked_by')->default(0);
            $table->integer('recommended_by')->default(0);
            $table->integer('approved_by')->default(0);
            $table->string('status')->comment('Draft,Saved,Cancelled')->nullable();
            $table->integer('user_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joi_head');
    }
};
