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
        Schema::table('jo_rfq_material_offer', function (Blueprint $table) {
            $table->double('offer_no')->default(0)->after('jor_material_details_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jo_rfq_material_offer', function (Blueprint $table) {
            //
        });
    }
};
