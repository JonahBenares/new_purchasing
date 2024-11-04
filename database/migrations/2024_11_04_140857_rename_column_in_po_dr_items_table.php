<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::table('po_dr_items', function (Blueprint $table) {
        //     //
        // });
        try {
            DB::statement("ALTER TABLE po_dr_items CHANGE `pr_dr_details_id` `pr_details_id` INT");
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('po_dr_items', function (Blueprint $table) {
            //
        });
    }
};
