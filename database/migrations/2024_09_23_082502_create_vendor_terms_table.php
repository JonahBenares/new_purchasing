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
        Schema::create('vendor_terms', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_head_id')->default(0);
            $table->integer('vendor_details_id')->default(0);
            $table->integer('order_no')->default(0);
            $table->string('terms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_terms');
    }
};
