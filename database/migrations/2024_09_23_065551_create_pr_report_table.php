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
        Schema::create('pr_report', function (Blueprint $table) {
            $table->id();
            $table->string('pr_no')->nullable();
            $table->integer('pr_details_id')->default(0);
            $table->integer('po_head_id')->default(0);
            $table->string('method')->nullable();
            $table->double('pr_qty')->default(0);
            $table->double('po_qty')->default(0);
            $table->double('delivered_qty')->default(0);
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_report');
    }
};
