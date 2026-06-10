<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('tbl_attendance', function (Blueprint $table) {
            $table->id();

            // ── Employee reference ────────────────────────────────
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('tbl_employee_info')
                  ->onDelete('cascade');

            $table->string('fullname', 100)->nullable();
            $table->string('position', 100)->nullable();

            // ── Date ─────────────────────────────────────────────
            $table->date('t_date')->nullable()->index();

            // ── Time punches ──────────────────────────────────────
            // AM session (before 12:00)
            $table->time('am_time_in')->nullable();
            $table->time('am_time_out')->nullable();

            // PM session (after 13:00)
            $table->time('pm_time_in')->nullable();
            $table->time('pm_time_out')->nullable();

            // ── Computed values ───────────────────────────────────

            $table->decimal('total_hours', 5, 2)->default(0);
            $table->integer('late_minutes')->default(0);
            $table->integer('undertime_minutes')->default(0);

            // ── Source tracking ───────────────────────────────────
            // Which ZKTeco .txt file or manual entry created this row
            $table->string('import_source', 150)->nullable()
                  ->comment('ZKTeco filename or "manual"');

            $table->timestamps();

            // ── Indexes ───────────────────────────────────────────
            // Prevent duplicate records for same employee on same day
            $table->unique(['user_id', 't_date']);

            // Fast lookups by date range
            $table->index(['t_date', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_attendance');
    }
};
