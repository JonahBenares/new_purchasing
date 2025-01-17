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
        Schema::table('joi_coc', function (Blueprint $table) {
            $table->string('coc_no')->nullable()->after('joi_head_id');
            $table->integer('user_id')->default(0)->after('saved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joi_coc', function (Blueprint $table) {
            //
        });
    }
};
