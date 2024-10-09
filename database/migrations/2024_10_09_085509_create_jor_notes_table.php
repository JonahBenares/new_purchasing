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
        Schema::create('jor_notes', function (Blueprint $table) {
            $table->id();
            $table->integer('jor_head_id')->default(0);
            $table->text('notes')->nullable();
            $table->string('cancelled_date')->nullable();
            $table->string('cancelled_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jor_notes');
    }
};
