<?php

namespace Database\Factories;

use App\Models\Cycle;
use App\Models\Treatment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Treatment>
 */
class TreatmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cycle_id' => Cycle::factory(),
            'applied_on' => now()->subWeek()->toDateString(),
            'agent' => 'Cal agrícola',
            'reason' => 'Corrección de pH',
        ];
    }
}
