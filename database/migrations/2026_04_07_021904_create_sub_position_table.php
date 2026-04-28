<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_position', function (Blueprint $table) {
            $table->id();
            $table->string('main_pos', 100)->nullable();
            $table->string('sub_position', 100)->nullable();
            $table->integer('idunno')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_position');
    }
};
