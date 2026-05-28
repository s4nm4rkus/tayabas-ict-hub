<?php

// ══════════════════════════════════════════════════════════════════════════════
//  FILE 1: database/migrations/2025_01_01_000004_create_ict_helpdesk_requests_table.php
// ══════════════════════════════════════════════════════════════════════════════

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('ict_helpdesk_requests', function (Blueprint $table) {
            $table->id();

            // Ticket reference e.g. ICT-HD-2025-00001
            $table->string('ticket_no', 30)->unique();

            // Submitter
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Form fields
            $table->date('date_filed');
            $table->string('requesting_office', 200);
            $table->string('requesting_name', 200);
            $table->text('details_request');
            $table->date('date_requested');
            $table->string('time_requested', 20);
            $table->text('specific_instructions')->nullable();

            // Status
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
        Schema::dropIfExists('ict_helpdesk_requests');
    }
};