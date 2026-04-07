<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_eligibility', function (Blueprint $table) {
            $table->string('user_id', 100)->primary();
            $table->string('type_eligibility', 100)->nullable();
            $table->date('date_issue')->nullable();
            $table->string('validity', 100)->nullable();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('tbl_employee_info')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_eligibility');
    }
};