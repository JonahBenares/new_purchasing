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
        Schema::create('joi_dr', function (Blueprint $table) {
            $table->id();
            $table->integer('joi_head_id')->default(0);
            $table->string('joi_no')->nullable();
            $table->string('jor_no')->nullable();
            $table->string('dr_no')->nullable();
            $table->string('dr_date')->nullable();
            $table->string('site_pr')->nullable();
            $table->string('delivery_date')->nullable();
            $table->string('status')->nullable()->comment('Saved, Cancelled');
            $table->string('cancelled_by')->nullable();
            $table->string('cancelled_date')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->integer('user_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joi_dr');
    }
};
