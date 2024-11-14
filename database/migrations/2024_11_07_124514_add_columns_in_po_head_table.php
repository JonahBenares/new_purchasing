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
        Schema::table('po_head', function (Blueprint $table) {
            $table->string('approved_date')->nullable()->after('revision_no');
            $table->integer('approved_by_rev')->default(0)->after('approved_date');
            $table->text('approved_reason')->nullable()->after('approved_by_rev');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('po_head', function (Blueprint $table) {
            //
        });
    }
};
