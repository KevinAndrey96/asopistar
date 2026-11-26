<?php

namespace Database\Factories;

use App\Models\Cycle;
use App\Models\FeedBrand;
use App\Models\Feeding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Feeding>
 */
class FeedingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cycle_id' => Cycle::factory(),
            'feed_brand_id' => FeedBrand::factory(),
            'fed_on' => now()->subWeek()->toDateString(),
            'kilograms' => 25,
            'protein_percent' => 32,
        ];
    }
}
