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
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->string('job_id',25);
            $table->string('job_title');
            $table->smallInteger('emp_id');
            $table->string('emp_name');
            $table->string('email')->unique();
            $table->string('report_to_job_id',25);
            $table->string('report_to_name');
            $table->smallInteger('role_priority');
            $table->smallInteger('job_level');
            $table->string('is_root');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
