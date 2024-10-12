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
        Schema::create('jor_head', function (Blueprint $table) {
            $table->id();
            $table->string('location')->nullable();
            $table->string('jor_no')->nullable();
            $table->string('site_jor')->nullable();
            $table->string('date_prepared')->nullable();
            $table->integer('department_id')->default(0);
            $table->string('department_name')->nullable();
            $table->string('dept_code')->nullable();
            $table->string('duration')->nullable();
            $table->string('completion_date')->nullable();
            $table->string('delivery_date')->nullable();
            $table->text('purpose')->nullable();
            $table->string('requestor')->nullable();
            $table->text('general_description')->nullable();
            $table->integer('urgency')->default(0);
            $table->string('process_code')->nullable();
            $table->string('method')->nullable()->comment('Upload/Manual');
            $table->string('status')->nullable()->comment('Draft, Saved, Cancelled, Closed');
            $table->integer('user_id')->default(0);
            $table->string('cancelled_by')->nullable();
            $table->string('cancelled_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jor_head');
    }
};
