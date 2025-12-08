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
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('queue_number')->unique();
            $table->integer('window_number');
            $table->enum('status', ['waiting', 'substep1', 'substep2', 'substep3', 'completed'])->default('waiting');
            $table->integer('current_substep')->nullable();
            $table->timestamps();

            $table->index(['window_number', 'status']);
            $table->index('queue_number');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
