<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    
    
    public function definition(): array
    {
        return [
            'users_id' => fake()->numberBetween(1, 2),
            'annonces_id' => fake()->numberBetween(1, 2),
            //'appariement_id' => fake()->numberBetween(1, 2),
            'montant' => fake()->numberBetween(10, 2000),
            'devise' => fake()->randomElement(['USD','EUR','XAF']),
            'type' => fake()->randomElement(['DEPOT', 'RETRAIT', 'TRANSFERT', 'COMPENSATION']),
            'statut' => fake()->randomElement(['EN_ATTENTE', 'EN_COURS', 'REUSSIE', 'ECHOUEE']),
            'reference' => fake()->uuid(),
            'methode_paiement'=> 'electronic',
            'date_traitement'=> now(),
            'description'=> fake()->sentence(),
        ];
    }
}