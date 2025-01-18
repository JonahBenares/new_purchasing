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
        Schema::create('joi_coc', function (Blueprint $table) {
            $table->id();
            $table->integer('joi_head_id')->default(0);
            $table->integer('approved_by')->default(0);
            $table->integer('checked_by')->default(0);
            $table->text('warranty')->nullable();
            $table->string('date_prepared')->nullable();
            $table->integer('saved')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joi_coc');
    }
};
