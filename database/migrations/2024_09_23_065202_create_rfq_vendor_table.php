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
        Schema::create('rfq_vendor', function (Blueprint $table) {
            $table->id();
            $table->integer('rfq_head_id')->default(0);
            $table->string('pr_no')->nullable();
            $table->integer('vendor_details_id')->default(0);
            $table->string('vendor_name')->nullable();
            $table->string('award_status')->comment('draft,saved')->nullable();
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
        Schema::dropIfExists('rfq_vendor');
    }
};
