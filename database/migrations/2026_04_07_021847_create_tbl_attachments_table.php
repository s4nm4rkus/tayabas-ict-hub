<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 100)->nullable();
            $table->string('title', 100)->nullable();
            $table->string('conduct_by', 200)->nullable();
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->string('file_path', 500)->nullable();

            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('tbl_employee_info')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_attachments');
    }
};