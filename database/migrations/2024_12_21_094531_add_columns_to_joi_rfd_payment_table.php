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
        Schema::table('joi_rfd_payment', function (Blueprint $table) {
            $table->string('payment_details')->nullable()->after('payment_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('joi_rfd_payment', function (Blueprint $table) {
            //
        });
    }
};
