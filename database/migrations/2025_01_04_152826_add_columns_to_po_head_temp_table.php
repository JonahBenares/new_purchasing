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
        Schema::table('po_head_temp', function (Blueprint $table) {
            $table->integer('checked_by')->default(0)->after('internal_comment');
            $table->integer('recommended_by')->default(0)->after('checked_by');
            $table->integer('approved_by')->default(0)->after('recommended_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('po_head_temp', function (Blueprint $table) {
            //
        });
    }
};
