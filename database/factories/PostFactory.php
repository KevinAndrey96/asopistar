<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(5),
            'excerpt' => fake()->sentence(12),
            'body' => fake()->paragraphs(3, true),
            'published_on' => now()->subDays(3)->toDateString(),
        ];
    }
}
