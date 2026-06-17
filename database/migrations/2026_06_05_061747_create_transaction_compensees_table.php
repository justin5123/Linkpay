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
        Schema::create('transactions_compensees', function (Blueprint $table) {

            // $table->id();

            // $table->string('reference')->unique();

            // $table->foreignId('appariement_id')
            //     ->constrained('appariements')
            //     ->cascadeOnDelete();

            // $table->foreignId('payeur_a_id')
            //     ->constrained('users');

            // $table->foreignId('payeur_b_id')
            //     ->constrained('users');

            // $table->decimal('montant_a',15,2);

            // $table->decimal('montant_b',15,2);

            // $table->enum('statut',[
            //     'EN_ATTENTE',
            //     'EN_COURS',
            //     'PAYER_A',
            //     'PAYER_B',
            //     'TERMINEE',
            //     'LITIGE',
            //     'ANNULEE'
            // ])->default('EN_ATTENTE');

            // $table->timestamp('date_debut')->nullable();

            // $table->timestamp('date_fin')->nullable();

            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_compensees');
    }
};
