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
        Schema::table('aoq_head', function (Blueprint $table) {
            $table->integer('prepared_by')->default(0)->change();
            $table->integer('received_by')->default(0)->change();
            $table->integer('recommended_by')->default(0)->change();
            $table->integer('approved_by')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aoq_head', function (Blueprint $table) {
            //
        });
    }
};
