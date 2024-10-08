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
        // Schema::table('pr_head', function (Blueprint $table) {
        //     $table->renameColumn(`pr_date`, `date_issued`);
        // });
        try {
            DB::statement("ALTER TABLE pr_head CHANGE `pr_date` `date_issued` VARCHAR(255)");
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pr_head', function (Blueprint $table) {
            //$table->renameColumn(`date_issued`, `pr_date`);
        });
    }
};
