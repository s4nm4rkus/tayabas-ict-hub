<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_service_rec', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 100)->nullable();
            $table->date('inclu_from')->nullable();
            $table->date('inclu_to')->nullable();
            $table->string('designation', 100)->nullable();
            $table->string('service_status', 100)->nullable();
            $table->string('salary_step', 100)->nullable();
            $table->string('salary_grade', 100)->nullable();
            $table->string('station', 100)->nullable();
            $table->string('branch', 100)->nullable();
            $table->string('separation', 50)->nullable();
            $table->string('position', 50)->nullable();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('tbl_employee_info')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_service_rec');
    }
};