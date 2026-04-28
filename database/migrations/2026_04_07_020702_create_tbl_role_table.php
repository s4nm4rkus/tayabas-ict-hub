<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_role', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('role_desc', 100)->nullable();
            $table->enum('role_cat', ['Teaching', 'Non-Teaching'])->nullable();
            $table->enum('role_type', ['Department Head', 'Employee'])->nullable();
            $table->string('role_head', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_role');
    }
};
