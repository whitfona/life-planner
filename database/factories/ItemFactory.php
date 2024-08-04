<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Box;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => fake()->text(),
            'photo_url' => fake()->slug(),
            'box_id' => Box::factory(),
        ];
    }
}