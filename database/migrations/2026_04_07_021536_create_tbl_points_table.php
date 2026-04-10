<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userid')->nullable()->constrained('tbl_employee_info')->onDelete('cascade');
            $table->date('t_date')->nullable();
            $table->string('acc_points', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_points');
    }
};