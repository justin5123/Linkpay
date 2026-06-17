<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Code unique de parrainage (ex: LIN-ABC123)
            $table->string('referral_code')->unique()->nullable()->after('id');

            // ID de l'utilisateur qui a parrainé celui-ci
            $table->foreignId('referred_by')->nullable()->constrained('users')->onDelete('set null');

            // Bonus total accumulé
            $table->decimal('referral_bonus', 15, 2)->default(0);

            // Nombre de parrainages réussis
            $table->integer('referral_count')->default(0);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['referral_code', 'referred_by', 'referral_bonus', 'referral_count']);
        });
    }
};
