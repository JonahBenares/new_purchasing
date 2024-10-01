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
        Schema::table('pr_head', function (Blueprint $table) {
            $table->integer('petty_cash')->change()->default(0)->comment('1-Yes, 0-No');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pr_head', function (Blueprint $table) {
            //
        });
    }
};
