<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queue_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('prefix')->unique();
            $table->integer('last_sequence')->default(0);
            $table->timestamps();

            $table->index('prefix');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_sequences');
    }
};
