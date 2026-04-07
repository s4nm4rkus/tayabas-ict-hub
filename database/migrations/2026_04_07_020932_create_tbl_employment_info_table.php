<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_employment_info', function (Blueprint $table) {
            $table->string('user_id', 100)->primary();
            $table->string('position', 100)->nullable();
            $table->string('sub_position', 100)->nullable();
            $table->date('date_orig_appoint')->nullable();
            $table->string('salary_grade', 100)->nullable();
            $table->string('salary_step', 100)->nullable();
            $table->date('salary_effect_date')->nullable();
            $table->string('vice', 100)->nullable();
            $table->string('vice_reason', 100)->nullable();
            $table->string('nature_appoint', 100)->nullable();
            $table->string('status_appoint', 100)->nullable();
            $table->string('plantilla_item_no', 100)->nullable();
            $table->string('plantilla_incu', 100)->nullable();
            $table->string('school_office_assign', 100)->nullable();
            $table->string('school_detailed_office_assign', 100)->nullable();
            $table->date('designated_from')->nullable();
            $table->date('designated_to')->nullable();
            $table->string('separation', 100)->nullable();
            $table->date('separation_date')->nullable();
            $table->string('head', 100)->nullable();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('tbl_employee_info')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_employment_info');
    }
};