<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('tbl_leave', function (Blueprint $table) {

            $table->id();

            // ── Existing Core Fields ───────────────────────────────────────
            $table->foreignId('user_id')->nullable()->constrained('tbl_employee_info')->onDelete('cascade');
            $table->string('fullname', 100)->nullable();
            $table->string('position', 100)->nullable();
            $table->string('leavetype', 100)->nullable();
            $table->date('date_applied')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('total_days', 100)->nullable();
            $table->string('attachments', 100)->nullable();
            $table->string('leave_status', 100)->nullable();
            // Statuses: Pending Head | Pending HR | Pending AO | Pending ASDS | Approved | Declined | Cancelled
            $table->string('remarks', 100)->nullable();
            $table->string('dept_head', 100)->nullable();   // Head user id (existing)
            $table->string('approve_by', 100)->nullable();  // HR user id (existing)
            $table->string('leavefile', 100)->nullable();   // attachment path (existing)

            // ── Form 6 Fields ──────────────────────────────────────────────
            $table->string('department', 100)->nullable();
            $table->string('salary', 100)->nullable();
            $table->text('leave_types')->nullable();        // e.g. "Vacation Leave,Sick Leave"
            $table->text('leave_details')->nullable();      // e.g. "Within Philippines:Tayabas;In Hospital:Flu"
            $table->string('commutation', 50)->nullable();  // "Required" or "Not Required"
            $table->string('number_of_days', 50)->nullable();
            $table->string('inclusive_dates', 150)->nullable();

            // ── Step 2: HR — Leave Credits (Section 7A) ───────────────────
            $table->string('hr_as_of', 50)->nullable();
            $table->string('vl_earned', 50)->nullable();
            $table->string('vl_less', 50)->nullable();
            $table->string('vl_balance', 50)->nullable();
            $table->string('sl_earned', 50)->nullable();
            $table->string('sl_less', 50)->nullable();
            $table->string('sl_balance', 50)->nullable();

            // ── Step 3: AO — Administrative Officer (Section 7B area) ─────
            $table->unsignedBigInteger('ao_id')->nullable();
            $table->string('ao_action', 20)->nullable();    // Approved | Declined
            $table->timestamp('ao_at')->nullable();
            $table->string('ao_remarks', 150)->nullable();

            // ── Step 4: ASDS — Final Approval (Section 7C / 7D) ───────────
            $table->unsignedBigInteger('asds_id')->nullable();
            $table->string('asds_action', 20)->nullable();  // Approved | Declined
            $table->timestamp('asds_at')->nullable();
            $table->string('asds_days_with_pay', 50)->nullable();
            $table->string('asds_days_without_pay', 50)->nullable();
            $table->string('asds_others', 100)->nullable();
            $table->text('asds_disapproval')->nullable();

            // ── E-Signature Watermark (approver name at time of signing) ──
            // These store the full name of the approver shown on the Form 6
            $table->string('head_esign_name', 150)->nullable();
            $table->string('hr_esign_name', 150)->nullable();
            $table->string('ao_esign_name', 150)->nullable();
            $table->string('asds_esign_name', 150)->nullable();

            $table->timestamps();

            // ── Foreign Keys ───────────────────────────────────────────────
            $table->foreign('ao_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('asds_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_leave');
    }
};
