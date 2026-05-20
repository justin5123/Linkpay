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
        Schema::create('notifications', function (Blueprint $table) {

            $table->id();

            // Utilisateur concerné
            $table->foreignId('users_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Type de notification
            $table->enum('type', [
                'INSCRIPTION',
                'KYC',
                'WALLET',
                'ANNONCE',
                'MATCHING',
                'TRANSACTION',
                'SUPPORT',
                'SECURITE',
                'FRAUDE',
                'SYSTEME'
            ]);

            // Titre
            $table->string('titre');

            $table->enum('canal', [
                    'APP',
                    'EMAIL',
                    'EMAIL_APP',
                    'SMS'
                ])->default('APP');

            // Contenu
            $table->text('message');

            // Lecture
            $table->boolean('est_lu')
                ->default(false);

            // Priorité
            $table->enum('priorite', [
                'FAIBLE',
                'NORMALE',
                'ELEVEE',
                'URGENTE'
            ])->default('NORMALE');

            // Lien éventuel (ticket, transaction, annonce)
            $table->string('lien_action')
                ->nullable();

            // Date lecture
            $table->timestamp('date_lecture')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};