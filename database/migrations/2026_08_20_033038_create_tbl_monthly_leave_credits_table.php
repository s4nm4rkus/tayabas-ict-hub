<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('tbl_monthly_leave_credits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('tbl_employee_info')
                ->onDelete('cascade');

            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month'); // 1-12

            // Actual service days counted for this period, after applying
            // fractional-day rounding rules (Section 12) and excluding
            // LWOP (Section 11). Stored separately from lwop_days so the
            // computation is auditable without re-deriving from attendance.
            $table->decimal('actual_service_days', 5, 2)->default(0);
            $table->decimal('lwop_days', 5, 2)->default(0);

            $table->decimal('vl_earned', 8, 3)->default(0);
            $table->decimal('sl_earned', 8, 3)->default(0);

            // Free-text note on what table/rule version produced this
            // computation (e.g. "ORL Table I v2021") — for traceability
            // per Section 50, without hard-coding a rigid schema for rule
            // versions we may not have fully catalogued yet.
            $table->string('computation_source', 100)->nullable();

            $table->foreignId('computed_by')->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamp('computed_at')->nullable();

            $table->timestamps();

            // The actual idempotency guarantee — the DB rejects a second
            // row for the same employee+year+month outright, regardless
            // of what the application code does.
            $table->unique(['employee_id', 'year', 'month'], 'monthly_credit_unique_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_monthly_leave_credits');
    }
};