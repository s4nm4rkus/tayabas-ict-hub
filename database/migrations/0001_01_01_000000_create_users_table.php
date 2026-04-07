<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->string('id', 100)->primary();
        $table->string('username', 100)->unique();
        $table->string('password');
        $table->string('user_pos', 100)->nullable();
        $table->enum('user_stat', ['Enabled', 'Disabled'])->default('Disabled');
        $table->boolean('pass_change')->default(false);
        $table->string('otp', 100)->nullable();
        $table->timestamp('otp_expires_at')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('users');
}
};