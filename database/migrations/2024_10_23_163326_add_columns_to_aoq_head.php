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
            $table->string('cancelled_date')->nullable()->after('aoq_status');
            $table->integer('cancelled_by')->default(0)->after('aoq_status');
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
