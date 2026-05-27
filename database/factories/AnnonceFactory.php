<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AnnonceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'users_id' => null,
            'type' => fake()->randomElement(['ENVOI','RECEPTION']),
            'montant_source' => fake()->numberBetween(50, 2000),
            'montant_cible' => fake()->numberBetween(50, 2000),
            'devise_source' => fake()->randomElement(['USD','EUR','XAF']),
            'devise_cible' => fake()->randomElement(['USD','EUR','XAF']),
            'statut' => 'EN_ATTENTE',
            'taux_change'=>fake()->randomElement(['0.05','0.9','0.6']),
            'pays_source'=>fake()->randomElement(['cameroun','congo','tchad','usa']),
            'pays_destination'=>fake()->randomElement(['france','canada','cameroun']),
            'est_appariee'=>fake()->randomElement(['0','1']),
        ];
    }
}