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
        Schema::table('joi_material_details', function (Blueprint $table) {
            $table->string('status')->nullable()->after('reference_joi_material_details_id');
            $table->string('cancelled_date')->nullable()->after('status');
            $table->integer('cancelled_by')->default(0)->after('cancelled_date');
            $table->text('cancelled_reason')->nullable()->after('cancelled_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joi_material_details', function (Blueprint $table) {
            //
        });
    }
};
