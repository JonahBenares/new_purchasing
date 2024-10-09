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
        Schema::create('jor_labor_details', function (Blueprint $table) {
            $table->id();
            $table->integer('jor_head_id')->default(0);
            $table->text('scope_of_work')->nullable();
            $table->double('quantity')->default(0);
            $table->string('uom')->nullable();
            $table->double('unit_cost')->default(0);
            $table->double('total_cost')->default(0);
            $table->string('recom_date')->nullable();
            $table->string('recom_status')->nullable()->comment('Open/Closed');
            $table->string('status')->nullable()->comment('Saved, Cancelled');
            $table->string('cancelled_date')->nullable();
            $table->string('cancelled_by')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jor_labor_details');
    }
};
