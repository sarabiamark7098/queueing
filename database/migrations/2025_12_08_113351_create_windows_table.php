<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('windows', function (Blueprint $table) {
            $table->id();
            $table->integer('window_number')->unique();
            $table->unsignedBigInteger('substep1_queue_id')->nullable();
            $table->unsignedBigInteger('substep2_queue_id')->nullable();
            $table->unsignedBigInteger('substep3_queue_id')->nullable();
            $table->integer('last_queue_number')->default(0);
            $table->timestamps();

            $table->index('window_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('windows');
    }
};
