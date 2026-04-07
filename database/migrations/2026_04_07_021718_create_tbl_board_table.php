<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_board', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 100)->nullable();
            $table->string('role', 100)->nullable();
            $table->string('title', 100)->nullable();
            $table->string('description', 1000)->nullable();
            $table->timestamp('date_time')->nullable();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_board');
    }
};