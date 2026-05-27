<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'users_id' => fake()->numberBetween(2, 4),
            'type' => fake()->randomElement(['INSCRIPTION', 'KYC', 'WALLET', 'ANNONCE']),
            'canal' => fake()->randomElement(['EMAIL','APP']),
            'titre' => fake()->sentence(),
            'message' => fake()->text(100),
            'est_lu' => false,
        ];
    }
}