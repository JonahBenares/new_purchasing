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
        Schema::create('joi_rfd', function (Blueprint $table) {
            $table->id();
            $table->integer('joi_head_id')->deafult(0);
            $table->string('rfd_no')->nullable();
            $table->string('joi_no')->nullable();
            $table->string('jor_no')->nullable();
            $table->string('rfd_date')->nullable();
            $table->string('due_date')->nullable();
            $table->string('check_due')->nullable();
            $table->string('check_name')->nullable();
            $table->string('company')->nullable();
            $table->string('bank_no')->nullable();
            $table->string('pay_to')->nullable();
            $table->string('mode')->comment('check=1,cash=2')->nullable();
            $table->text('notes')->nullable();
            $table->double('sub_total')->default(0);
            $table->integer('ewt_perc')->default(0);
            $table->double('ewt_amount')->default(0);
            $table->integer('ewt')->default(0);
            $table->integer('vat')->default(0);
            $table->double('vat_amount')->default(0);
            $table->double('payment_amount')->default(0);
            $table->text('payment_description')->nullable();
            $table->integer('retention_perc')->default(0);
            $table->double('retention_amount')->default(0);
            $table->double('balance')->default(0);
            $table->string('status')->comment('Draft,Saved,Cancelled')->nullable();
            $table->integer('cancelled_by')->default(0);
            $table->string('cancelled_date')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->integer('checked_by')->default(0);
            $table->integer('endorsed_by')->default(0);
            $table->integer('approved_by')->default(0);
            $table->integer('noted_by')->default(0);
            $table->integer('user_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joi_rfd');
    }
};
