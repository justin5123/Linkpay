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
        Schema::create('moyens_paiements', function (Blueprint $table) {

            $table->id();

            // Utilisateur propriétaire
            $table->foreignId('users_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Type moyen paiement
            $table->enum('type', [
                'MOBILE_MONEY',
                'BANQUE',
                'PAYPAL',
                'CARTE_BANCAIRE',
                'INTERAC',
                'WALLET_NUMERIQUE'
            ]);

            // Fournisseur
            $table->string('fournisseur');

            // Identifiant paiement
            // numéro téléphone, email PayPal, IBAN, etc.
            $table->string('identifiant_compte');

            // Pays associé
            $table->string('pays');

            // Devise supportée
            $table->string('devise', 10);

            // Vérification
            $table->boolean('est_verifie')
                ->default(false);

            // Moyen principal
            $table->boolean('est_principal')
                ->default(false);

            // Activation
            $table->boolean('est_actif')
                ->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moyens_paiements');
    }
};