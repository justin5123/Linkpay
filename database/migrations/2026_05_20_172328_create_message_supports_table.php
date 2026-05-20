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
        Schema::create('messages_support', function (Blueprint $table) {

            $table->id();

            // Ticket concerné
            $table->foreignId('support_ticket_id')
                ->constrained('support_tickets')
                ->cascadeOnDelete();

            // Expéditeur du message
            $table->foreignId('expediteur_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Destinataire
            $table->foreignId('destinataire_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Contenu du message
            $table->longText('message');

            // Pièce jointe éventuelle
            $table->string('piece_jointe')
                ->nullable();

            // Message lu ?
            $table->boolean('est_lu')
                ->default(false);

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
        Schema::dropIfExists('messages_support');
    }
};