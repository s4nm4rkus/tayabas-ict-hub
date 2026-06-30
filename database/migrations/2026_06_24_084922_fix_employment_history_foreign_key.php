<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_employment_history', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')
                  ->references('id')
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
