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
        Schema::table('joi_dr', function (Blueprint $table) {
            $table->integer('print_identifier')->default(0)->after('identifier');
            $table->double('received')->default(0)->after('print_identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joi_dr', function (Blueprint $table) {
            //
        });
    }
};
