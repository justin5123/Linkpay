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
        Schema::create('cycle_compensations', function (Blueprint $table) {

            $table->id();

            // Référence unique du cycle
            $table->string('reference')
                ->unique();

            // Période du cycle
            $table->timestamp('date_debut');
            $table->timestamp('date_fin')->nullable();

            // Statut du cycle
            $table->enum('statut', [
                'OUVERT',
                'EN_CALCUL',
                'EN_COMPENSATION',
                'TERMINE',
                'ECHEC'
            ])->default('OUVERT');

            // Montants globaux
            $table->decimal('montant_total_envoi', 15, 2)
                ->default(0);

            $table->decimal('montant_total_reception', 15, 2)
                ->default(0);

            // Équilibrage global
            $table->decimal('solde_net', 15, 2)
                ->default(0);

            // Appariements inclus dans le cycle
            $table->integer('nombre_appariements')
                ->default(0);

            // Fonds de liquidité utilisé
            $table->decimal('fonds_liquidite_utilise', 15, 2)
                ->default(0);

            // Validation admin finance
            $table->foreignId('valide_par')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cycle_compensations');
    }
};