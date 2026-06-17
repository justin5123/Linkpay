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
        Schema::create('referral_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade'); // parrain
            $table->foreignId('referred_user_id')->constrained('users')->onDelete('cascade'); // filleul
            $table->decimal('amount', 15, 2); // montant du bonus
            $table->enum('type', ['SIGNUP_BONUS', 'DEPOSIT_BONUS'])->default('SIGNUP_BONUS');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_transactions');
    }
};
