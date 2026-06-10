<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->string('emp_code', 30)->index();          // raw employee_no from ZKTeco
            $table->dateTime('punch_time')->index();
            $table->tinyInteger('punch_state')->default(0);   // 0=check-in, 1=check-out
            $table->tinyInteger('verify_type')->default(1);   // 1=fingerprint, 4=face, 15=password
            $table->string('source_file', 100)->nullable();   // which .txt file this came from
            $table->timestamps();

            $table->index(['emp_code', 'punch_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
