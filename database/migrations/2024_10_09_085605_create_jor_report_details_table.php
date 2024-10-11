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
        // Schema::create('jor_report_details', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('jor_no')->nullable();
        //     $table->integer('jor_labor_details_id')->default(0);
        //     $table->integer('jor_material_details_id')->default(0);
        //     $table->text('item_description')->nullable();
        //     $table->text('scope_of_work')->nullable();
        //     $table->integer('jorfq_offer_id')->default(0);
        //     $table->text('labor_offer')->nullable();
        //     $table->text('material_offer')->nullable();
        //     $table->double('labor_qty')->default(0);
        //     $table->double('material_qty')->default(0);
        //     $table->double('labor_delivered_qty')->default(0);
        //     $table->double('material_delivered_qty')->default(0);
        //     $table->double('labor_jo_qty')->default(0);
        //     $table->double('material_jo_qty')->default(0);
        //     $table->double('labor_djo_qty')->default(0);
        //     $table->double('material_djo_qty')->default(0);
        //     $table->string('uom')->nullable();
        //     $table->string('method')->nullable();
        //     $table->string('status')->nullable();
        //     $table->integer('identifier')->default(0)->comment('1-Labor, 2-Materials');
        //     $table->text('status_remarks')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jor_report_details');
    }
};
