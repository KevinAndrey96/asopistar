<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\TreatmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Sanitary record: a preventive or corrective product applied to the pond.
 *
 * @property int $id
 * @property int $cycle_id
 * @property CarbonImmutable $applied_on
 * @property string $agent
 * @property string $reason
 * @property-read Cycle $cycle
 */
#[Fillable(['applied_on', 'agent', 'reason'])]
class Treatment extends Model
{
    /** @use HasFactory<TreatmentFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['applied_on' => 'immutable_date'];
    }

    /** @return BelongsTo<Cycle, $this> */
    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }
}
