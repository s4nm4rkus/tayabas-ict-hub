<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_employee_info', function (Blueprint $table) {
            $table->id();                                         // own auto-increment pk
            $table->foreignId('user_id')                         // integer FK to users.id
                ->unique()
                ->constrained('users')
                ->onDelete('cascade');
            $table->string('last_name', 100)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('middle_name', 100)->nullable();
            $table->string('ex_name', 100)->nullable();
            $table->string('gender', 100)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('place_of_birth', 100)->nullable();
            $table->string('contact_num', 100)->nullable();
            $table->string('gov_email', 100)->nullable();
            $table->string('employee_no', 100)->nullable();
            $table->string('philhealth', 100)->nullable();
            $table->string('pagibig', 100)->nullable();
            $table->string('TIN', 100)->nullable();
            $table->string('street', 100)->nullable();
            $table->string('street_brgy', 100)->nullable();
            $table->string('municipality', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('disability', 100)->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->string('bp_no', 100)->nullable();
            $table->date('date_encoded')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_employee_info');
    }
};
