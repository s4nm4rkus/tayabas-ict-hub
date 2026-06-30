<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('tbl_service_rec', function (Blueprint $table) {
            // Drop the WRONG foreign key
            $table->dropForeign('tbl_service_rec_user_id_foreign');
        });

        Schema::table('tbl_service_rec', function (Blueprint $table) {
            // Add the CORRECT foreign key
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('tbl_employee_info')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_service_rec', function (Blueprint $table) {
            $table->dropForeign('tbl_service_rec_user_id_foreign');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('tbl_employee_info')
                  ->onDelete('cascade');
        });
    }
};
