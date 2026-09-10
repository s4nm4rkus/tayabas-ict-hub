<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('tbl_leave_balances', function (Blueprint $table) {
            $table->id();

            // One balance row per employee — this is a CACHE/SNAPSHOT only.
            // The source of truth is tbl_leave_credit_transactions; this table
            // exists purely so the dashboard / Form 6 don't need to sum the
            // entire ledger on every page load. It must only ever be written
            // to by the LeaveCreditService, never edited directly by hand.
            $table->foreignId('employee_id')
                ->unique()
                ->constrained('tbl_employee_info')
                ->onDelete('cascade');

            $table->decimal('vl_balance', 8, 3)->default(0);
            $table->decimal('sl_balance', 8, 3)->default(0);

            // Date this snapshot is accurate as of — mirrors the "as of" date
            // shown on Form 6 Section 7A.
            $table->date('as_of_date')->nullable();

            // Who/when this snapshot was last recalculated — for HR visibility,
            // not for authorization (that lives in the ledger transactions).
            $table->foreignId('updated_by')->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_leave_balances');
    }
};