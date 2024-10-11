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
        Schema::table('rfq_vendor', function (Blueprint $table) {
            $table->string('status')->nullable()->comment('Draft,Saved')->after('vendor_identifier');
            $table->string('canvassed_date')->nullable()->after('vendor_identifier');
            $table->integer('canvassed_by')->default(0)->after('vendor_identifier');
            $table->integer('canvassed')->default(0)->after('vendor_identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rfq_vendor', function (Blueprint $table) {
            //
        });
    }
};
