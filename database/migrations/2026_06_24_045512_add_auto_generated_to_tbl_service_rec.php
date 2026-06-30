<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('tbl_service_rec', function (Blueprint $table) {
            // Distinguish auto-generated rows from manually typed rows
            $table->boolean('is_auto_generated')->default(false)->after('separation');

            // Pre-computed annual salary (monthly step value × 12)
            $table->decimal('annual_salary', 12, 2)->nullable()->after('salary_step');

            // Change reason carried from history for reference
            $table->string('change_reason', 50)->nullable()->after('is_auto_generated');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_service_rec', function (Blueprint $table) {
            $table->dropColumn(['is_auto_generated', 'annual_salary', 'change_reason']);
        });
    }
};
