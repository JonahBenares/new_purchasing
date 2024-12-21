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
        Schema::table('po_rfd', function (Blueprint $table) {
            $table->double('grand_total')->default(0)->after('notes');
            $table->integer('identifier')->default(0)->after('status');
            $table->integer('show_ewt')->default(0)->after('identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('po_rfd', function (Blueprint $table) {
            //
        });
    }
};
