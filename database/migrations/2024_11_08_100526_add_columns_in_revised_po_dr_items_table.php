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
        Schema::table('revised_po_dr_items', function (Blueprint $table) {
            $table->integer('po_head_rev_id')->default(0)->after('id');
            $table->integer('po_dr_rev_id')->default(0)->after('po_head_rev_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revised_po_dr_items', function (Blueprint $table) {
            //
        });
    }
};
