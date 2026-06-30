<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('tbl_employment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tbl_employee_info')->onDelete('cascade');

            // Position snapshot at time of change
            $table->string('position', 100)->nullable();
            $table->string('sub_position', 100)->nullable();
            $table->unsignedTinyInteger('salary_grade')->nullable();
            $table->unsignedTinyInteger('salary_step')->nullable();
            $table->string('nature_appoint', 100)->nullable();
            $table->string('status_appoint', 100)->nullable();
            $table->string('station', 150)->nullable();

            // The date this history entry took effect
            $table->date('effective_date');

            // Closed when next change happens; null = current/active
            $table->date('end_date')->nullable();

            // ORIGINAL | PROMOTION | DEMOTION | TRANSFER | RECLASSIFICATION | REINSTATEMENT
            $table->string('change_reason', 50)->default('ORIGINAL');

            // Step increment anchor:
            // - PROMOTION  → resets to effective_date (counter restarts)
            // - DEMOTION   → carries over step_anchor from previous row (counter continues)
            // - ORIGINAL   → same as effective_date
            $table->date('step_anchor')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'effective_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_employment_history');
    }
};
