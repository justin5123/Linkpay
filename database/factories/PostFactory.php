<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'users_id' => null,
            'contenu' => fake()->sentence(),
            'media' => null,
            'likes_count' => 0,
            'comments_count' => 0,
        ];
    }
}