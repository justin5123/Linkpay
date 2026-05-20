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
        Schema::create('wallets', function (Blueprint $table) {

            $table->id();

            // Relation avec l'utilisateur
            $table->foreignId('users_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Solde du wallet
            $table->decimal('solde', 15, 2)
                ->default(0);
            $table->string('numero_compte')->unique();
            // Devise du wallet
            $table->string('devise', 10);

            // Mot de passe/PIN wallet
            $table->string('pin_wallet');

            // Statut du wallet
            $table->enum('statut', [
                'ACTIF',
                'SUSPENDU',
                'BLOQUE',
                'EN_ATTENTE_KYC'
            ])->default('EN_ATTENTE_KYC');

            // Wallet activé ou non
            $table->boolean('est_actif')
                ->default(false);

            // Sécurité anti-fraude
            $table->integer('tentatives_pin_echouees')
                ->default(0);

            // Blocage temporaire
            $table->timestamp('bloque_jusqua')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};