<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_salary', function (Blueprint $table) {
            $table->id();
            $table->integer('salary_grade')->nullable();
            $table->string('step_1', 100)->nullable();
            $table->string('step_2', 100)->nullable();
            $table->string('step_3', 100)->nullable();
            $table->string('step_4', 100)->nullable();
            $table->string('step_5', 100)->nullable();
            $table->string('step_6', 100)->nullable();
            $table->string('step_7', 100)->nullable();
            $table->string('step_8', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_salary');
    }
};
