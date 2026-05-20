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
        Schema::table('appariements', function (Blueprint $table) {
            $table->foreignId('cycle_compensation_id')
                  ->nullable()
                  ->constrained('cycle_compensations')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appariements', function (Blueprint $table) {
            $table->dropForeign(['cycle_compensation_id']);
            $table->dropColumn('cycle_compensation_id');
        });
    }
};