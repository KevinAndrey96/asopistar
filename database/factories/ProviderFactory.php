<?php

namespace Database\Factories;

use App\Domain\Tax\Nit;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Provider>
 */
class ProviderFactory extends Factory
{
    public function definition(): array
    {
        $number = (string) fake()->unique()->numberBetween(800_000_000, 999_999_999);

        return [
            'name' => 'Estación Piscícola '.fake()->lastName(),
            'nit' => $number.'-'.Nit::checkDigitOf($number),
            'city' => fake()->randomElement(['Villavicencio', 'Neiva', 'Ibagué', 'Granada']),
            'phone' => '3'.fake()->numerify('#########'),
            'contact_name' => fake()->name(),
        ];
    }
}
