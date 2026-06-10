<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('tbl_leave', function (Blueprint $table) {
            $table->string('employee_esign_path', 255)->nullable()->after('leavefile');
            $table->string('head_esign_path', 255)->nullable()->after('head_esign_name');
            $table->string('hr_esign_path', 255)->nullable()->after('hr_esign_name');
            $table->string('ao_esign_path', 255)->nullable()->after('ao_esign_name');
            $table->string('asds_esign_path', 255)->nullable()->after('asds_esign_name');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_leave', function (Blueprint $table) {
            $table->dropColumn([
                'employee_esign_path',
                'head_esign_path',
                'hr_esign_path',
                'ao_esign_path',
                'asds_esign_path',
            ]);
        });
    }
};
