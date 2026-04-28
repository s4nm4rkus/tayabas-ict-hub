<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('tbl_employee_info')->onDelete('cascade');
            $table->enum('req_type', ['CSR', 'COE', 'COEC', 'CNA', 'CLB'])->nullable();
            $table->date('date_req')->nullable();
            $table->time('time_req')->nullable();
            $table->date('approve_date')->nullable();
            $table->time('approve_time')->nullable();
            $table->string('req_status', 100)->nullable();
            $table->string('approve_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_request');
    }
};
