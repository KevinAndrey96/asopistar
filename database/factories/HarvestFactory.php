<?php

namespace Database\Factories;

use App\Models\Cycle;
use App\Models\Harvest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Harvest>
 */
class HarvestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cycle_id' => Cycle::factory(),
            'harvested_on' => now()->subDay()->toDateString(),
            'fish_count' => 100,
            'kilograms' => 50,
            'destination' => 'Plaza de mercado',
            'ice_slaughter_kg' => 20,
            'ice_cooling_kg' => 25,
            'ice_transport_kg' => 30,
        ];
    }
}
