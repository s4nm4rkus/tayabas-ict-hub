<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('appointment_options', function (Blueprint $table) {
            $table->id();
            $table->enum('option_type', ['nature_appoint', 'status_appoint']);
            $table->string('option_value', 100);
            $table->string('option_label', 150);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['option_type', 'option_value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_options');
    }
};
