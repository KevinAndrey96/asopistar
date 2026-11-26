<?php

namespace Database\Factories;

use App\Models\Species;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Species>
 */
class SpeciesFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Tilapia '.fake()->unique()->word(),
            'scientific_name' => 'Oreochromis sp.',
            'fry_until_week' => 4,
            'grow_out_until_week' => 12,
            'harvest_week' => 24,
            'market_weight_grams' => 500,
        ];
    }
}
