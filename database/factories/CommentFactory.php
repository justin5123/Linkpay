<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        $user = User::all();

        return [
            'post_id' => fake()->numberBetween(1, 2),
            'users_id' => fake()->numberBetween(1, 2),
            'contenu' => fake()->sentence(),
        ];
    }
}