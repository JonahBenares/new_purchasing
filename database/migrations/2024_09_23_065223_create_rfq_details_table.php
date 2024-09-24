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
        Schema::create('rfq_details', function (Blueprint $table) {
            $table->id();
            $table->integer('rfq_head_id')->default(0);
            $table->integer('rfq_vendor_id')->default(0);
            $table->integer('pr_details_id')->default(0);
            $table->string('pr_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfq_details');
    }
};
