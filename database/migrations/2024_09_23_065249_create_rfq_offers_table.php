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
        Schema::create('rfq_offers', function (Blueprint $table) {
            $table->id();
            $table->integer('rfq_head_id')->default(0);
            $table->integer('rfq_vendor_id')->default(0);
            $table->integer('rfq_details_id')->default(0);
            $table->integer('pr_details_id')->default(0);
            $table->string('pr_no')->nullable();
            $table->text('offer')->nullable();
            $table->string('uom')->nullable();
            $table->double('unit_price')->default(0);
            $table->string('currency')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('canvassed_by')->default(0);
            $table->string('canvassed_date')->nullable();
            $table->integer('awarded')->default(0);
            $table->string('status')->comment('draft,saved')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfq_offers');
    }
};
