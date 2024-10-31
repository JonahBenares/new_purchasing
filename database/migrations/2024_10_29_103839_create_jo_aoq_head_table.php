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
        Schema::create('jo_aoq_head', function (Blueprint $table) {
            $table->id();
            $table->integer('jo_rfq_head_id')->default(0);
            $table->string('aoq_no')->nullable();
            $table->string('jor_no')->nullable();
            $table->string('aoq_date')->nullable();
            $table->string('date_needed')->nullable();
            $table->string('status')->nullable()->comment('Draft, Saved, Cancelled');
            $table->string('aoq_status')->nullable()->comment('For TE, Done TE, Awarded');
            $table->string('awarded_by')->nullable();
            $table->string('awarded_date')->nullable();
            $table->string('cancelled_by')->nullable();
            $table->string('cancelled_date')->nullable();
            $table->string('prepared_by')->nullable();
            $table->string('received_by')->nullable();
            $table->string('award_recommended_by')->nullable();
            $table->string('recommended_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->integer('user_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jo_aoq_head');
    }
};
