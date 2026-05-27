<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupportTicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'users_id' => fake()->numberBetween(3, 6),
            'assigne_a' => null,
            'categorie' => fake()->randomElement(['KYC', 'TRANSACTION', 'WALLET', 'ANNONCE']),
            'sujet' => fake()->sentence(),
            'description' => fake()->text(),
            'priorite' => fake()->randomElement(['FAIBLE', 'NORMALE', 'ELEVEE', 'URGENTE']),
            'statut' => fake()->randomElement(['OUVERT', 'EN_COURS', 'EN_ATTENTE_UTILISATEUR']),
            'reference' => fake()->uuid(),
        ];
    }
}