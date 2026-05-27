<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;


class MessageSupportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'support_ticket_id' => fake()->numberBetween(3, 6),
            'expediteur_id' => fake()->numberBetween(1, 6),
            'destinataire_id' => fake()->numberBetween(4, 4),
            'message' => fake()->sentence(),
            'est_lu' => false,
        ];
    }
}