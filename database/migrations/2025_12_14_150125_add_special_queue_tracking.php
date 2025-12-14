<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->boolean('is_special')->default(false)->after('status');
            $table->boolean('is_manual')->default(false)->after('is_special');
        });
    }

    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->dropColumn(['is_special', 'is_manual']);
        });
    }
};
