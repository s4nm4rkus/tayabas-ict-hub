<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_eligibility', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained('tbl_employee_info')->onDelete('cascade');
            $table->string('type_eligibility', 100)->nullable();
            $table->date('date_issue')->nullable();
            $table->string('validity', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_eligibility');
    }
};