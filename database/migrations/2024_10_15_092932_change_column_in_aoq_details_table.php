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
        Schema::table('aoq_details', function (Blueprint $table) {
            $table->dropColumn('aoq_details_id');
            $table->integer('aoq_head_id')->default(0)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aoq_details', function (Blueprint $table) {
            //
        });
    }
};
