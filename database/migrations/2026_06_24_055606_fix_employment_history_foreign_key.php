<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('tbl_employment_history', function (Blueprint $table) {
            // Drop the WRONG foreign key
            $table->dropForeign('tbl_employment_history_user_id_foreign');
        });

        Schema::table('tbl_employment_history', function (Blueprint $table) {
            // Add the CORRECT foreign key
            $table->foreign('user_id')
                  ->references('user_id')    // ← Points to user_id, not id
                  ->on('tbl_employee_info')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_employment_history', function (Blueprint $table) {
            $table->dropForeign('tbl_employment_history_user_id_foreign');
        });

        Schema::table('tbl_employment_history', function (Blueprint $table) {
            // Restore the old (wrong) FK if you roll back
            $table->foreign('user_id')
                  ->references('id')
                  ->on('tbl_employee_info')
                  ->onDelete('cascade');
        });
    }
};
