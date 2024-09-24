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
        Schema::create('po_dr', function (Blueprint $table) {
            $table->id();
            $table->integer('pr_head_id')->default(0);
            $table->string('po_no')->nullable();
            $table->string('pr_no')->nullable();
            $table->string('site_pr')->nullable();
            $table->string('dr_date')->nullable();
            $table->string('dr_no')->nullable();
            $table->string('status')->nullable()->comment('Saved, Cancelled');
            $table->string('delivery_date')->nullable();
            $table->integer('user_id')->default(0);
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
        Schema::dropIfExists('po_dr');
    }
};
