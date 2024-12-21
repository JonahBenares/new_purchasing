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
        Schema::table('joi_rfd', function (Blueprint $table) {
            $table->double('grand_total')->default(0)->after('notes');
            $table->string('checked_by_name')->nullable()->after('checked_by');
            $table->string('noted_by_name')->nullable()->after('noted_by');
            $table->string('endorsed_by_name')->nullable()->after('endorsed_by');
            $table->string('approved_by_name')->nullable()->after('approved_by');
            $table->integer('received_by')->default(0)->after('noted_by_name');
            $table->string('received_by_name')->nullable()->after('received_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joi_rfd', function (Blueprint $table) {
            //
        });
    }
};
