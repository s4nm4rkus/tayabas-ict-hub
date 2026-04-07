<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_leavepoints', function (Blueprint $table) {
            $table->id();
            $table->string('leave_day', 100)->nullable();
            $table->string('point_equi', 100)->nullable();
            $table->string('month', 100)->nullable();
            $table->string('leave_earn', 100)->nullable();
            $table->string('vacation_leave', 100)->nullable();
            $table->string('leave_earn_wop', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_leavepoints');
    }
};