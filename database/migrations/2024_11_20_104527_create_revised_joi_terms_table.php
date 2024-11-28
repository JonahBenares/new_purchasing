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
        Schema::create('revised_joi_terms', function (Blueprint $table) {
            $table->id();
            $table->integer('joi_head_rev_id')->default(0);
            $table->integer('joi_terms_id')->default(0);
            $table->integer('joi_head_id')->default(0);
            $table->text('terms')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revised_joi_terms');
    }
};
