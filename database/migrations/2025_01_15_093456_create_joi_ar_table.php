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
        Schema::create('joi_ar', function (Blueprint $table) {
            $table->id();
            $table->integer('joi_head_id')->default(0);
            $table->string('ar_date')->nullable();
            $table->integer('year')->default(0);
            $table->integer('series')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joi_ar');
    }
};
