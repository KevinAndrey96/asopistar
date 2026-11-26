<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Database\Factories\RecipeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Fish recipe; ingredients and steps are stored one per line.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $summary
 * @property string $ingredients
 * @property string $steps
 * @property string|null $image_path
 */
#[Fillable(['title', 'summary', 'ingredients', 'steps', 'image_path'])]
class Recipe extends Model
{
    /** @use HasFactory<RecipeFactory> */
    use HasFactory, HasSlug;

    /** @return list<string> */
    public function ingredientList(): array
    {
        return self::lines($this->ingredients);
    }

    /** @return list<string> */
    public function stepList(): array
    {
        return self::lines($this->steps);
    }

    protected function slugSource(): string
    {
        return 'title';
    }

    /** @return list<string> */
    private static function lines(string $text): array
    {
        return array_values(array_filter(array_map(trim(...), preg_split('/\R/', $text) ?: []), fn (string $line) => $line !== ''));
    }
}
