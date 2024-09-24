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
        Schema::create('pr_head', function (Blueprint $table) {
            $table->id();
            $table->string('pr_date')->nullable();
            $table->string('location')->nullable();
            $table->string('pr_no')->nullable();
            $table->string('site_pr')->nullable();
            $table->string('date_prepared')->nullable();
            $table->integer('department_id')->default(0);
            $table->string('department_name')->nullable();
            $table->string('dept_code')->nullable();
            $table->string('enduse')->nullable();
            $table->string('purpose')->nullable();
            $table->string('requestor')->nullable();
            $table->integer('urgency')->default(0);
            $table->string('process_code')->nullable();
            $table->string('method')->nullable()->comment('Upload/Manual');
            $table->string('status')->nullable()->comment('Draft, Saved, Cancelled, Closed');
            $table->integer('user_id')->default(0);
            $table->string('cancelled_by')->nullable();
            $table->string('cancelled_date')->nullable();
            $table->string('petty_cash')->nullable()->comment('1-Yes, 0-No');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_head');
    }
};
