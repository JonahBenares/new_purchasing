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
        Schema::create('jo_rfq_head', function (Blueprint $table) {
            $table->id();
            $table->integer('jor_head_id')->default(0);
            $table->string('jor_no')->nullable();
            $table->string('rfq_name')->nullable();
            $table->string('rfq_no')->nullable();
            $table->string('rfq_date')->nullable();
            $table->integer('user_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jo_rfq_head');
    }
};
