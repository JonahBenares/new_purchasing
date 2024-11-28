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
        Schema::create('joi_head_temp', function (Blueprint $table) {
            $table->id();
            $table->integer('joi_head_id')->default(0);
            $table->double('discount')->default(0);
            $table->double('discount_material')->default(0);
            $table->integer('vat')->default(0);
            $table->double('vat_amount')->default(0);
            $table->double('vat_in_ex')->default(0);
            $table->double('grand_total')->default(0);
            $table->string('revision_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joi_head_temp');
    }
};
