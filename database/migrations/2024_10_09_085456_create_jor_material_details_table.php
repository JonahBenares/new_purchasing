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
        Schema::create('jor_material_details', function (Blueprint $table) {
            $table->id();
            $table->integer('jor_head_id')->default(0);
            $table->string('date_needed')->nullable();
            $table->text('item_description')->nullable();
            $table->double('quantity')->default(0);
            $table->string('uom')->nullable();
            $table->string('pn_no')->nullable();
            $table->double('wh_stocks')->default(0);
            $table->string('status')->nullable()->comment('Saved, Cancelled, Referred, OnHold');
            $table->string('recom_date')->nullable();
            $table->string('recom_status')->nullable()->comment('Open/Closed');
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
        Schema::dropIfExists('jor_material_details');
    }
};
