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
        Schema::table('revised_po_head', function (Blueprint $table) {
            $table->double('vat_percent')->default(0)->after('vat_amount');
            $table->integer('vat_in_ex')->default(0)->after('vat_percent');
            $table->string('cancelled_date')->nullable()->after('status');
            $table->integer('cancelled_by')->default(0)->after('cancelled_date');
            $table->text('cancelled_reason')->nullable()->after('cancelled_by');
            $table->text('internal_comment')->nullable()->after('cancelled_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('revised_po_head', function (Blueprint $table) {
            //
        });
    }
};
