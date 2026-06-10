<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('ict_dts_requests', function (Blueprint $table) {
            $table->id();

            // ── Ticket reference ───────────────────────────────────────────
            // e.g. ICT-DTS-2025-00001
            $table->string('ticket_no', 30)->unique();

            // ── Submitter ─────────────────────────────────────────────────
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // ── Common fields ─────────────────────────────────────────────
            $table->date('date_reported');
            $table->string('dts_number', 100);
            $table->string('requester_name', 200);
            $table->string('mobile_number', 11);
            $table->string('school', 200);

            // ── Request type ──────────────────────────────────────────────
            $table->enum('request_type', [
                'Retrieve',
                'Edit Document Title',
                'Cancel Transaction',
                'Reset Password',
                'Add Document',
                'New User Email Address',
            ]);

            // ── Conditional fields (nullable) ─────────────────────────────
            // Retrieve
            $table->string('unit_name', 150)->nullable();
            $table->string('reason', 500)->nullable();

            // Edit Document Title
            $table->string('new_title', 300)->nullable();
            $table->string('edit_reason', 500)->nullable();

            // Cancel Transaction
            $table->string('cancel_reason', 500)->nullable();

            // Reset Password
            $table->string('email_address', 200)->nullable();

            // Add Document
            $table->string('document_type', 200)->nullable();
            $table->unsignedSmallInteger('process_days')->nullable();

            // New User Email Address
            $table->string('new_email', 200)->nullable();

            // ── Status ────────────────────────────────────────────────────
            $table->enum('status', [
                'Pending',
                'In Progress',
                'Resolved',
                'Closed',
                'Cancelled',
            ])->default('Pending');

            $table->text('admin_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ict_dts_requests');
    }
};