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
        Schema::table('things_todo', function (Blueprint $table) {
            $table->string('date_finished')->nullable()->after('status');
            $table->integer('user_id')->default(0)->after('date_finished');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('things_todo', function (Blueprint $table) {
            //
        });
    }
};
