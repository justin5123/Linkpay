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
        Schema::create('support_tickets', function (Blueprint $table) {

            $table->id();

            // Utilisateur ayant créé le ticket
            $table->foreignId('users_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Agent support assigné
            $table->foreignId('assigne_a')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Catégorie problème
            $table->enum('categorie', [
                'KYC',
                'TRANSACTION',
                'WALLET',
                'ANNONCE',
                'REMBOURSEMENT',
                'SECURITE',
                'COMPTE',
                'AUTRE'
            ]);

            // Sujet du ticket
            $table->string('sujet');

            // Message utilisateur
            $table->longText('description');

            // Priorité
            $table->enum('priorite', [
                'FAIBLE',
                'NORMALE',
                'ELEVEE',
                'URGENTE'
            ])->default('NORMALE');

            // Statut
            $table->enum('statut', [
                'OUVERT',
                'EN_COURS',
                'EN_ATTENTE_UTILISATEUR',
                'RESOLU',
                'FERME'
            ])->default('OUVERT');

            // Référence ticket
            $table->string('reference')
                ->unique();

            // Dates métier
            $table->timestamp('date_resolution')
                ->nullable();

            $table->timestamp('date_fermeture')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};