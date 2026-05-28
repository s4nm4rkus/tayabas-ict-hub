<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('ict_tickets', function (Blueprint $table) {
            $table->id();

            // ── Ticket reference ───────────────────────────────────────────
            // e.g. ICT-2025-00001
            $table->string('ticket_no', 30)->unique();

            // ── Submitter (linked to users + employee info) ────────────────
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // ── Requester fields (pre-filled from employee record) ─────────
            $table->string('full_name', 200);
            $table->string('position', 200);
            $table->string('department', 150);
            $table->date('date_reported');

            // ── Assistance types (stored as JSON array) ────────────────────
            // e.g. ["Repair","Network Management"]
            $table->json('assistance_types');
            $table->string('others_text', 255)->nullable();   // filled when "Others" is checked

            // ── Description ───────────────────────────────────────────────
            $table->text('description');

            // ── Ticket status ─────────────────────────────────────────────
            // Pending → In Progress → Resolved → Closed  |  Cancelled
            $table->enum('status', [
                'Pending',
                'In Progress',
                'Resolved',
                'Closed',
                'Cancelled',
            ])->default('Pending');

            // ── ICT admin notes (optional, filled by admin) ───────────────
            $table->text('admin_notes')->nullable();
            $table->foreignId('assigned_to')->nullable()      // user_id of ICT technician
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ict_tickets');
    }
};
