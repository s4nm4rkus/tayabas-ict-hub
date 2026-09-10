<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    /**
     * The FK added by 2026_06_24_060050_fix_service_rec_foreign_key.php
     * (tbl_service_rec.user_id -> tbl_employee_info.user_id) conflicts with
     * ServiceRecordGenerator::generate(), which documents that column as
     * expecting tbl_employee_info.id instead. Enforcing the wrong target
     * blocks legitimate service-record generation (e.g. user_id 378 case).
     * Removing the constraint here as a forward migration, since the
     * original migration already ran and editing it has no effect.
     * The correct target column should be resolved separately before
     * re-adding any FK here.
     */
    public function up(): void
    {
        $exists = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'tbl_service_rec'
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
              AND CONSTRAINT_NAME = 'tbl_service_rec_user_id_foreign'
        ");

        if (! empty($exists)) {
            Schema::table('tbl_service_rec', function (Blueprint $table) {
                $table->dropForeign('tbl_service_rec_user_id_foreign');
            });
        }
    }

    public function down(): void
    {
        // Intentionally no-op — see note above. Do not blindly re-add the
        // FK here without first resolving the correct target column.
    }
};