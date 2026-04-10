<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();                                        // standard auto-increment integer
            $table->string('user_id', 100)->unique()->nullable(); // ICTHUB-2025-0001 format
            $table->string('username', 100)->unique();
            $table->string('password');
            $table->string('user_pos', 100)->nullable();
            $table->string('user_stat', 50)->default('Enabled');
            $table->boolean('pass_change')->default(false);
            $table->string('otp', 10)->nullable();
            $table->datetime('otp_expires_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};