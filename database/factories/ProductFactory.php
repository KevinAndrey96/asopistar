<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Filete '.fake()->unique()->word(),
            'description' => fake()->sentence(10),
            'price_cop' => 18000,
            'unit' => 'kg',
            'available' => true,
        ];
    }
}
