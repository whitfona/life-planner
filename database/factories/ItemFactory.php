<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => fake()->text(),
            'photo_url' => fake()->slug(),
        ];
    }
}