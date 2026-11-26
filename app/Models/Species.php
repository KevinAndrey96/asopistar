<?php

namespace App\Models;

use App\Domain\Aquaculture\SpeciesProfile;
use Database\Factories\SpeciesFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $scientific_name
 * @property int $fry_until_week
 * @property int $grow_out_until_week
 * @property int $harvest_week
 * @property int $market_weight_grams
 */
#[Fillable(['name', 'scientific_name', 'fry_until_week', 'grow_out_until_week', 'harvest_week', 'market_weight_grams'])]
class Species extends Model
{
    /** @use HasFactory<SpeciesFactory> */
    use HasFactory;

    protected $table = 'species';

    protected function casts(): array
    {
        return [
            'fry_until_week' => 'integer',
            'grow_out_until_week' => 'integer',
            'harvest_week' => 'integer',
            'market_weight_grams' => 'integer',
        ];
    }

    /** @return HasMany<Cycle, $this> */
    public function cycles(): HasMany
    {
        return $this->hasMany(Cycle::class);
    }

    public function profile(): SpeciesProfile
    {
        return new SpeciesProfile(
            $this->fry_until_week,
            $this->grow_out_until_week,
            $this->harvest_week,
            $this->market_weight_grams,
        );
    }
}
