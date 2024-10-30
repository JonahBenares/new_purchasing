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
        Schema::create('jo_rfq_vendor', function (Blueprint $table) {
            $table->id();
            $table->integer('jo_rfq_head_id')->default(0);
            $table->string('jor_no')->nullable();
            $table->integer('vendor_details_id')->default(0);
            $table->string('vendor_name')->nullable();
            $table->string('vendor_identifier')->nullable();
            $table->integer('canvassed')->default(0);
            $table->integer('canvassed_by')->default(0);
            $table->string('canvassed_date')->nullable();
            $table->string('status')->nullable()->comment('Draft,Saved');
            $table->string('award_status')->comment('Draft,Saved')->nullable();
            $table->string('due_date')->nullable();
            $table->integer('prepared_by')->default(0);
            $table->integer('noted_by')->default(0);
            $table->integer('approved_by')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jo_rfq_vendor');
    }
};
