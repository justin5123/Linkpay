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
        Schema::create('annonces', function (Blueprint $table) {

            $table->id();

            // Utilisateur créateur de l'annonce
            $table->foreignId('users_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Type d'annonce
            $table->enum('type', [
                'ENVOI',
                'RECEPTION'
            ]);

            // Montants
            $table->decimal('montant_source', 15, 2);

            $table->decimal('montant_cible', 15, 2)
                ->nullable();

            // Devises
            $table->string('devise_source', 10);

            $table->string('devise_cible', 10);

            // Pays
            $table->string('pays_source');

            $table->string('pays_destination');

            // Taux de change API
            $table->decimal('taux_change', 15, 6)
                ->nullable();

            // Statut annonce
            $table->enum('statut', [
                'EN_ATTENTE',
                'APPARIEE',
                'ANNULEE',
                'EXPIREE',
                'TERMINEE'
            ])->default('EN_ATTENTE');

            // Matching
            $table->boolean('est_appariee')
                ->default(false);

            // Date expiration (24h)
            $table->timestamp('expire_le')
                ->nullable();

            // Date appariement
            $table->timestamp('date_appariement')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};