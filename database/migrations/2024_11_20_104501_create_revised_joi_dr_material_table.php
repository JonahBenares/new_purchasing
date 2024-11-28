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
        Schema::create('revised_joi_dr_material', function (Blueprint $table) {
            $table->id();
            $table->integer('joi_head_rev_id')->default(0);
            $table->integer('joi_dr_rev_id')->default(0);
            $table->integer('joi_dr_id')->default(0);
            $table->integer('joi_material_details_id')->default(0);
            $table->integer('jor_material_details_id')->default(0);
            $table->integer('jo_rfq_material_offer_id')->default(0);
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
        Schema::dropIfExists('revised_joi_dr_material');
    }
};
