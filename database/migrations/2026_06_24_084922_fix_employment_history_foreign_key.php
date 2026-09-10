<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only drop the old foreign key if it actually exists on this DB.
        // (On this environment, no such key existed yet — the original
        // unconditional dropForeign(['user_id']) failed because of that.)
        $exists = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'tbl_employment_history'
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
              AND CONSTRAINT_NAME = 'tbl_employment_history_user_id_foreign'
        ");

        if (! empty($exists)) {
            Schema::table('tbl_employment_history', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        Schema::table('tbl_employment_history', function (Blueprint $table) {
            // Corrected: references tbl_employee_info.user_id, not .id —
            // matches Employee::employmentHistories() ('user_id', 'user_id')
            // and confirmed by the orphan-check query finding zero mismatches
            // when joined on user_id = user_id.
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('tbl_employee_info')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_employment_history', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('tbl_employee_info')
                  ->onDelete('cascade');
        });
    }
};