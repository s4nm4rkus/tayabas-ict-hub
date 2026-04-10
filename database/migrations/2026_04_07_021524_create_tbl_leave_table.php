<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_leave', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('tbl_employee_info')->onDelete('cascade');
            $table->string('fullname', 100)->nullable();
            $table->string('position', 100)->nullable();
            $table->string('leavetype', 100)->nullable();
            $table->date('date_applied')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('total_days', 100)->nullable();
            $table->string('attachments', 100)->nullable();
            $table->string('leave_status', 100)->nullable();
            $table->string('remarks', 50)->nullable();
            $table->string('dept_head', 100)->nullable();
            $table->string('approve_by', 100)->nullable();
            $table->string('leavefile', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_leave');
    }
};