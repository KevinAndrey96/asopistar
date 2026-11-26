<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\FeedingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $cycle_id
 * @property int $feed_brand_id
 * @property CarbonImmutable $fed_on
 * @property string $kilograms
 * @property string $protein_percent
 * @property-read Cycle $cycle
 * @property-read FeedBrand $feedBrand
 */
#[Fillable(['feed_brand_id', 'fed_on', 'kilograms', 'protein_percent'])]
class Feeding extends Model
{
    /** @use HasFactory<FeedingFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'fed_on' => 'immutable_date',
            'kilograms' => 'decimal:2',
            'protein_percent' => 'decimal:1',
        ];
    }

    /** @return BelongsTo<Cycle, $this> */
    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    /** @return BelongsTo<FeedBrand, $this> */
    public function feedBrand(): BelongsTo
    {
        return $this->belongsTo(FeedBrand::class);
    }
}
