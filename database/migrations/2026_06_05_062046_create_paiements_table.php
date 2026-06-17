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
        Schema::create('paiements', function (Blueprint $table) {

            $table->id();

            $table->foreignId('transaction_compensee_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('users_id')
                ->constrained('users');

            $table->decimal('montant',15,2);

            $table->string('preuve');

            $table->timestamp('date_paiement');

            $table->enum('statut',[
                'EN_ATTENTE',
                'VALIDE',
                'REFUSE'
            ])->default('EN_ATTENTE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
