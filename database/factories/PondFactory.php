<?php

namespace Database\Factories;

use App\Models\Pond;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pond>
 */
class PondFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'code' => 'E-'.fake()->unique()->numberBetween(1, 9999),
            'area_m2' => fake()->randomElement([200, 300, 500, 800]),
            'water_flow_lps' => fake()->randomFloat(1, 1, 10),
            'equipment' => 'Aireador de paletas',
            'active' => true,
        ];
    }
}
