<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_attendance', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 100)->nullable();
            $table->string('fullname', 100)->nullable();
            $table->string('position', 100)->nullable();
            $table->date('t_date')->nullable();
            $table->time('am_time_in')->nullable();
            $table->time('am_time_out')->nullable();
            $table->time('pm_time_in')->nullable();
            $table->time('pm_time_out')->nullable();
            $table->string('total_hours', 100)->nullable();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('tbl_employee_info')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_attendance');
    }
};