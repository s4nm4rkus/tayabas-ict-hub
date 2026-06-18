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
        Schema::table('tbl_employment_info', function (Blueprint $table) {
            $table->string('station_code')->nullable()->after('school_office_assign');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_employment_info', function (Blueprint $table) {
            $table->dropColumn('station_code');
        });
    }
};
