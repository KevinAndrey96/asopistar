<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Product the association sells to the public, priced in Colombian pesos.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int $price_cop
 * @property string $unit
 * @property string|null $image_path
 * @property bool $available
 */
#[Fillable(['name', 'description', 'price_cop', 'unit', 'image_path', 'available'])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, HasSlug;

    protected function casts(): array
    {
        return [
            'price_cop' => 'integer',
            'available' => 'boolean',
        ];
    }

    protected function slugSource(): string
    {
        return 'name';
    }
}
