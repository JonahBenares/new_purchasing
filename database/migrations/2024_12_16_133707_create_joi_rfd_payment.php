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
        Schema::create('joi_rfd_payment', function (Blueprint $table) {
            $table->id();
            $table->integer('joi_rfd_id')->default(0);
            $table->integer('joi_id')->default(0);
            $table->string('rfd_date')->nullable();
            $table->text('payment_description')->nullable();
            $table->double('payment_amount')->default(0);
            $table->double('subtotal')->default(0);
            $table->double('ewt_vat')->default(0);
            $table->double('ewt_percent')->default(0);
            $table->double('ewt_amount')->default(0);
            $table->double('retention_percent')->default(0);
            $table->double('retention_amount')->default(0);
            $table->integer('user_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joi_rfd_payment');
    }
};
