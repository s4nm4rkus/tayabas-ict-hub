<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_messages', function (Blueprint $table) {
            $table->id();
            $table->string('userid', 100)->nullable();
            $table->string('receiver', 100)->nullable();
            $table->string('messages', 1000)->nullable();
            $table->string('subject_mes', 100)->nullable();
            $table->timestamp('date_time')->nullable();
            $table->enum('mes_status', [
                'Unread',
                'Read',
                'Deleted',
                'DeletedBySender',
            ])->default('Unread');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_messages');
    }
};
