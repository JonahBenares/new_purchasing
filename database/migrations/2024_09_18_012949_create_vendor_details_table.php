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
        Schema::create('vendor_details', function (Blueprint $table) {
            $table->id();
            $table->integer('vendor_head_id')->default(0);
            $table->string('address')->nullable();
            $table->string('terms')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('email')->nullable();
            $table->string('tin')->nullable();
            $table->string('type')->nullable();
            $table->text('notes')->nullable();
            $table->double('ewt')->default(0);
            $table->integer('vat')->default(0);
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_details');
    }
};
