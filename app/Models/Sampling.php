<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\SamplingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Biometric sampling: a sample of fish weighed together, plus the deaths
 * counted since the previous sampling.
 *
 * @property int $id
 * @property int $cycle_id
 * @property CarbonImmutable $sampled_on
 * @property int $sample_size
 * @property string $sample_weight_grams
 * @property int $mortality
 * @property-read Cycle $cycle
 */
#[Fillable(['sampled_on', 'sample_size', 'sample_weight_grams', 'mortality'])]
class Sampling extends Model
{
    /** @use HasFactory<SamplingFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'sampled_on' => 'immutable_date',
            'sample_size' => 'integer',
            'sample_weight_grams' => 'decimal:2',
            'mortality' => 'integer',
        ];
    }

    /** @return BelongsTo<Cycle, $this> */
    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    public function averageWeightGrams(): float
    {
        return (float) $this->sample_weight_grams / $this->sample_size;
    }
}
