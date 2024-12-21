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
            $table->integer('checked_by')->default(0)->after('ewt_amount');
            $table->string('checked_by_name')->nullable()->after('checked_by');
            $table->integer('noted_by')->default(0)->after('checked_by_name');
            $table->string('noted_by_name')->nullable()->after('noted_by');
            $table->integer('endorsed_by')->default(0)->after('noted_by_name');
            $table->string('endorsed_by_name')->nullable()->after('endorsed_by');
            $table->integer('approved_by')->default(0)->after('endorsed_by_name');
            $table->string('approved_by_name')->nullable()->after('approved_by');
            $table->integer('received_by')->default(0)->after('approved_by_name');
            $table->string('received_by_name')->nullable()->after('received_by');
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
