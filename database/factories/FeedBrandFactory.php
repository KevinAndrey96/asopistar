<?php

namespace Database\Factories;

use App\Models\FeedBrand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FeedBrand>
 */
class FeedBrandFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Concentrado '.fake()->unique()->word(),
            'protein_percent' => fake()->randomElement([24, 28, 32, 38, 45]),
        ];
    }
}
