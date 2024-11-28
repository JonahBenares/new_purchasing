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
        Schema::create('joi_dr_labor_temp', function (Blueprint $table) {
            $table->id();
            $table->integer('joi_dr_id')->default(0);
            $table->integer('joi_labor_details_id')->default(0);
            $table->integer('jor_labor_details_id')->default(0);
            $table->integer('jo_rfq_labor_offer_id')->default(0);
            $table->double('quantity')->default(0);
            $table->double('to_deliver')->default(0);
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joi_dr_labor_temp');
    }
};
