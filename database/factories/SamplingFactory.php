<?php

namespace Database\Factories;

use App\Models\Cycle;
use App\Models\Sampling;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sampling>
 */
class SamplingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cycle_id' => Cycle::factory(),
            'sampled_on' => now()->subWeek()->toDateString(),
            'sample_size' => 20,
            'sample_weight_grams' => 2000,
            'mortality' => 0,
        ];
    }
}
