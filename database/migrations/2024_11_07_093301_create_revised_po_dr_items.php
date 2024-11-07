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
        Schema::create('revised_po_dr_items', function (Blueprint $table) {
            $table->id();
            $table->integer('po_dr_id')->default(0);
            $table->integer('po_details_id')->default(0);
            $table->integer('pr_details_id')->default(0);
            $table->integer('rfq_offer_id')->default(0);
            $table->double('quantity')->default(0);
            $table->string('status')->nullable(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revised_po_dr_items');
    }
};
