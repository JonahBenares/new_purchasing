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
        Schema::table('joi_dr_material', function (Blueprint $table) {
            $table->double('delivered_qty_disp')->default(0)->after('delivered_qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joi_dr_material', function (Blueprint $table) {
            //
        });
    }
};
