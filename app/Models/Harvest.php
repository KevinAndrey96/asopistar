<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\HarvestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Harvest with the ice used along the cold chain: slaughter by thermal
 * shock, cooling and transport.
 *
 * @property int $id
 * @property int $cycle_id
 * @property CarbonImmutable $harvested_on
 * @property int $fish_count
 * @property string $kilograms
 * @property string $destination
 * @property string $ice_slaughter_kg
 * @property string $ice_cooling_kg
 * @property string $ice_transport_kg
 * @property-read Cycle $cycle
 */
#[Fillable(['harvested_on', 'fish_count', 'kilograms', 'destination', 'ice_slaughter_kg', 'ice_cooling_kg', 'ice_transport_kg'])]
class Harvest extends Model
{
    /** @use HasFactory<HarvestFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'harvested_on' => 'immutable_date',
            'fish_count' => 'integer',
            'kilograms' => 'decimal:2',
            'ice_slaughter_kg' => 'decimal:2',
            'ice_cooling_kg' => 'decimal:2',
            'ice_transport_kg' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<Cycle, $this> */
    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    public function iceKg(): float
    {
        return (float) $this->ice_slaughter_kg + (float) $this->ice_cooling_kg + (float) $this->ice_transport_kg;
    }
}
