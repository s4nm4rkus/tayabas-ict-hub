<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_educational_bg', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained('tbl_employee_info')->onDelete('cascade');
            $table->string('elementary', 200)->nullable();
            $table->string('elem_duration', 100)->nullable();
            $table->string('secondary', 200)->nullable();
            $table->string('second_duration', 100)->nullable();
            $table->string('college', 200)->nullable();
            $table->string('college_duration', 100)->nullable();
            $table->string('college_school', 100)->nullable();
            $table->string('vocational', 100)->nullable();
            $table->string('voca_duration', 100)->nullable();
            $table->string('voc_school', 100)->nullable();
            $table->string('masters_degree', 200)->nullable();
            $table->string('master_duration', 100)->nullable();
            $table->string('master_units', 100)->nullable();
            $table->string('doc_degree', 200)->nullable();
            $table->string('doc_duration', 100)->nullable();
            $table->string('doc_units', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_educational_bg');
    }
};
