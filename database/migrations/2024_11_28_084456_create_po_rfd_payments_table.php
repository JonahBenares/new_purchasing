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
        Schema::create('po_rfd_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('po_rfd_id')->default(0);
            $table->integer('po_head_id')->default(0);
            $table->string('rfd_date')->nullable();
            $table->string('payment_description')->nullable();
            $table->double('payment_amount')->default(0);
            $table->integer('user_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_rfd_payments');
    }
};
