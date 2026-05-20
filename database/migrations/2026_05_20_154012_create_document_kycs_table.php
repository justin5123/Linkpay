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
        Schema::create('documents_kyc', function (Blueprint $table) {

            $table->id();

            // Relation utilisateur
            $table->foreignId('utilisateur_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Type document
            $table->enum('type_document', [
                'CNI',
                'PASSEPORT',
                'PERMIS_SEJOUR',
                'PERMIS_CONDUIRE'
            ]);

            // Numéro document
            $table->string('numero_document');

            // Images document
            $table->string('image_recto');
            $table->string('image_verso')
                ->nullable();

            // =====================
            // VERIFICATION FACIALE
            // =====================

            // Selfie utilisateur
            $table->string('image_selfie')
                ->nullable();

            // Score biométrique
            $table->decimal('score_similarite', 5, 2)
                ->nullable();

            // =====================
            // VALIDATION KYC
            // =====================

            $table->enum('statut', [
                'EN_ATTENTE',
                'EN_ANALYSE',
                'VALIDE',
                'REJETE'
            ])->default('EN_ATTENTE');

            // Motif rejet
            $table->text('motif_rejet')
                ->nullable();

            // Agent conformité
            $table->foreignId('valide_par')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Dates métier
            $table->timestamp('date_soumission')
                ->nullable();

            $table->timestamp('date_validation')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_kyc');
    }
};