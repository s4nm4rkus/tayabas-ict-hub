<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('tbl_leave_credit_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('tbl_employee_info')
                ->onDelete('cascade');

            // OPENING_BALANCE | MONTHLY_EARNING | LEAVE_DEDUCTION | LWOP |
            // TARDINESS_DEDUCTION | MANUAL_ADJUSTMENT | TRANSFER_IN |
            // TRANSFER_OUT | MONETIZATION | TERMINAL_LEAVE | FORFEITURE |
            // CORRECTION
            // Kept as a plain string (not enum) so new types can be added
            // later without a schema migration.
            $table->string('transaction_type', 30);

            // Signed amounts — positive for credit/earning, negative for
            // deduction. Keeping both columns on every row (even if one is
            // usually 0) makes every transaction type uniform and easy to
            // sum without conditional logic per type.
            $table->decimal('vl_amount', 8, 3)->default(0);
            $table->decimal('sl_amount', 8, 3)->default(0);

            // Snapshot of tbl_leave_balances immediately before/after this
            // transaction was applied. This is what makes the ledger
            // self-auditing — you can verify tbl_leave_balances at any
            // point in time by replaying transactions, and immediately spot
            // if the cache ever drifted from the ledger.
            $table->decimal('vl_balance_before', 8, 3);
            $table->decimal('sl_balance_before', 8, 3);
            $table->decimal('vl_balance_after', 8, 3);
            $table->decimal('sl_balance_after', 8, 3);

            // Polymorphic-style reference to whatever caused this
            // transaction (e.g. reference_type = 'Leave', reference_id =
            // tbl_leave.id). No FK constraint here on purpose — the
            // reference can point at different tables depending on type
            // (a Leave application, a monthly computation run, a manual
            // HR adjustment with no source row at all, etc).
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();

            // The date this transaction is effective as of — may differ
            // from created_at (e.g. a monthly earning transaction created
            // today but effective as of the last day of that month).
            $table->date('effective_date');

            $table->text('description')->nullable();

            $table->foreignId('created_by')->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->foreignId('approved_by')->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();

            $table->index(['employee_id', 'effective_date']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_leave_credit_transactions');
    }
};