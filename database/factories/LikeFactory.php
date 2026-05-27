<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class LikeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'post_id' => fake()->numberBetween(1, 2),
            'users_id' => fake()->numberBetween(1, 2),
        ];
    }
}
