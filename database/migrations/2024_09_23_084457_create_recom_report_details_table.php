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
        Schema::create('recom_report_details', function (Blueprint $table) {
            $table->id();
            $table->integer('recom_report_head_id')->default(0);
            $table->integer('pr_details_id')->default(0);
            $table->string('pr_no')->nullable();
            $table->string('jo_no')->nullable();
            $table->text('item_description')->nullable();
            $table->double('recom_qty')->default(0);
            $table->string('uom')->nullable();
            $table->double('unit_price')->default(0);
            $table->string('currency')->nullable();
            $table->double('total_amount')->default(0);
            $table->string('point_user')->nullable();
            $table->string('purpose')->nullable();
            $table->string('enduse')->nullable();
            $table->string('requestor')->nullable();
            $table->integer('vendor_details_id')->default(0);
            $table->string('vendor_name')->nullable();
            $table->string('payment_terms')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->nullable()->comment('Open, Closed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recom_report_details');
    }
};
