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
        Schema::create('appariements', function (Blueprint $table) {

            $table->id();

            // Les deux annonces appariées
            $table->foreignId('annonce_envoi_id')
                ->constrained('annonces')
                ->cascadeOnDelete();

            $table->foreignId('annonce_reception_id')
                ->constrained('annonces')
                ->cascadeOnDelete();

            // $table->foreignId('cycle_compensation_id')
            //     ->nullable()
            //     ->constrained('cycle_compensations')
            //     ->nullOnDelete();

            // Montant réellement compensé
            $table->decimal('montant_compense', 15, 2);

            // Statut appariement
            $table->enum('statut', [
                'EN_ATTENTE_VALIDATION',
                'VALIDE',
                'EN_COURS',
                'TERMINE',
                'ANNULE',
                'ECHEC'
            ])->default('EN_ATTENTE_VALIDATION');

            // Validation système/fonds temporaire
            $table->boolean('couvert_par_fonds')
                ->default(false);

            // Référence unique
            $table->string('reference')
                ->unique();

            // Dates métier
            $table->timestamp('date_appariement')
                ->nullable();

            $table->timestamp('date_validation')
                ->nullable();

            $table->timestamp('date_fin')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appariements');
    }
};