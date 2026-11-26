<?php

namespace Database\Factories;

use App\Models\Cycle;
use App\Models\Pond;
use App\Models\Species;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cycle>
 */
class CycleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pond_id' => Pond::factory(),
            'species_id' => Species::factory(),
            'stocked_fish' => 1000,
            'initial_weight_grams' => 2,
            'stocked_on' => now()->subWeeks(10)->toDateString(),
            'closed_on' => null,
        ];
    }

    public function closed(): static
    {
        return $this->state(fn () => ['closed_on' => now()->subWeek()->toDateString()]);
    }
}
