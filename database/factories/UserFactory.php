<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('clave-de-prueba-1'),
            'role' => Role::Farmer,
            'document' => (string) fake()->unique()->numberBetween(10_000_000, 1_999_999_999),
            'gender' => fake()->randomElement(Gender::cases()),
            'phone' => '3'.fake()->numerify('#########'),
            'estate' => 'Finca '.fake()->lastName(),
            'vereda' => fake()->randomElement(['El Carmen', 'La Esperanza', 'San Isidro', 'Buenos Aires']),
            'active' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => Role::Admin, 'gender' => null, 'estate' => null, 'vereda' => null]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['active' => false]);
    }
}
