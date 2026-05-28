<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('ict_email_requests', function (Blueprint $table) {
            $table->id();

            // ── Ticket reference ───────────────────────────────────────────
            // e.g. ICT-EMAIL-2025-00001
            $table->string('ticket_no', 30)->unique();

            // ── Submitter ─────────────────────────────────────────────────
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // ── Form fields ───────────────────────────────────────────────
            $table->date('date_reported');
            $table->enum('request_type', [
                'New Email',
                'Reset Email',
                'Activation of Office 365',
            ]);
            $table->string('full_name', 200);
            $table->string('personal_email', 200);
            $table->string('cellphone', 11);
            $table->string('school_name', 200);

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
        Schema::dropIfExists('ict_email_requests');
    }
};
