<?php

namespace App\Models;

use Database\Factories\FeedBrandFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $protein_percent
 */
#[Fillable(['name', 'protein_percent'])]
class FeedBrand extends Model
{
    /** @use HasFactory<FeedBrandFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['protein_percent' => 'decimal:1'];
    }

    /** @return HasMany<Feeding, $this> */
    public function feedings(): HasMany
    {
        return $this->hasMany(Feeding::class);
    }
}
