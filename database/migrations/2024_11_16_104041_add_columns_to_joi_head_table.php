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
        Schema::table('joi_head', function (Blueprint $table) {
            $table->string('date_needed')->nullable()->after('joi_date');
            $table->string('completion_work')->nullable()->after('date_needed');
            $table->string('date_prepared')->nullable()->after('completion_work');
            $table->string('start_of_work')->nullable()->after('date_prepared');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joi_head', function (Blueprint $table) {
            //
        });
    }
};
