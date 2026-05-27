<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    public function definition(): array
    {
        return [
            'users_id' => null, // rempli dans seeder
            'solde' => fake()->randomFloat(2, 100, 5000),
            'devise' => fake()->randomElement(['USD','EUR','XAF','GBP']),
            'pin_wallet' => bcrypt('1234'),
            'est_actif' => true,
            'numero_compte' => fake()->unique()->numerify('6########'),
        ];
    }
}