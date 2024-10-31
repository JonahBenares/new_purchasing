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
        Schema::create('jo_rfq_labor_details', function (Blueprint $table) {
            $table->id();
            $table->integer('jo_rfq_head_id')->default(0);
            $table->integer('jo_rfq_vendor_id')->default(0);
            $table->string('jor_no')->nullable();
            $table->integer('jor_labor_details_id')->default(0);
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jo_rfq_labor_details');
    }
};
