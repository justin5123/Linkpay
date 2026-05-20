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
        Schema::create('transactions', function (Blueprint $table) {

            $table->id();

            // Utilisateur concerné
            $table->foreignId('users_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Wallet concerné
            $table->foreignId('annonces_id')
                ->constrained('annonces')
                ->cascadeOnDelete();

            // Appariement lié (nullable)
            $table->foreignId('appariement_id')
                ->nullable()
                ->constrained('appariements')
                ->nullOnDelete();

            // Type transaction
            $table->enum('type', [
                'DEPOT',
                'RETRAIT',
                'TRANSFERT',
                'COMPENSATION',
                'REMBOURSEMENT'
            ]);

            // Montant
            $table->decimal('montant', 15, 2);

            // Devise
            $table->string('devise', 10);

            // Référence unique
            $table->string('reference')
                ->unique();

            // Statut
            $table->enum('statut', [
                'EN_ATTENTE',
                'EN_COURS',
                'REUSSIE',
                'ECHOUEE',
                'ANNULEE'
            ])->default('EN_ATTENTE');

            // Moyen de paiement utilisé
            $table->string('methode_paiement')
                ->nullable();

            // Description
            $table->text('description')
                ->nullable();

            // Dates métier
            $table->timestamp('date_traitement')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};