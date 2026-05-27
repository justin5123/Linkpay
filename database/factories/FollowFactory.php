<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
class FollowFactory extends Factory
{
    public function definition(): array
    {
        $user = User::all();

        return [
            'follower_id' => fake()->numberBetween(1, 2),
            'following_id' => fake()->numberBetween(1, 2),
        ];
    }
}