<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recipe>
 */
class RecipeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => 'Tilapia '.fake()->unique()->word(),
            'summary' => fake()->sentence(10),
            'ingredients' => "1 tilapia roja\n2 limones\nSal",
            'steps' => "Limpia el pescado.\nAdoba con limón y sal.\nFríe hasta dorar.",
        ];
    }
}
