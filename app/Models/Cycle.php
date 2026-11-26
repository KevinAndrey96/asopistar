<?php

namespace App\Models;

use App\Domain\Aquaculture\CycleMetrics;
use App\Domain\Aquaculture\GrowthStage;
use Carbon\CarbonImmutable;
use Database\Factories\CycleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * One stocking of a pond, from the fingerlings entry to the last harvest.
 *
 * @property int $id
 * @property int $pond_id
 * @property int $species_id
 * @property int|null $provider_id
 * @property int $stocked_fish
 * @property string $initial_weight_grams
 * @property CarbonImmutable $stocked_on
 * @property CarbonImmutable|null $closed_on
 * @property-read Pond $pond
 * @property-read Species $species
 * @property-read Provider|null $provider
 * @property-read Sampling|null $latestSampling
 */
#[Fillable(['species_id', 'provider_id', 'stocked_fish', 'initial_weight_grams', 'stocked_on', 'closed_on'])]
class Cycle extends Model
{
    /** @use HasFactory<CycleFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'stocked_fish' => 'integer',
            'initial_weight_grams' => 'decimal:2',
            'stocked_on' => 'immutable_date',
            'closed_on' => 'immutable_date',
        ];
    }

    /** @return BelongsTo<Pond, $this> */
    public function pond(): BelongsTo
    {
        return $this->belongsTo(Pond::class);
    }

    /** @return BelongsTo<Species, $this> */
    public function species(): BelongsTo
    {
        return $this->belongsTo(Species::class);
    }

    /** @return BelongsTo<Provider, $this> */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    /** @return HasMany<Feeding, $this> */
    public function feedings(): HasMany
    {
        return $this->hasMany(Feeding::class);
    }

    /** @return HasMany<Sampling, $this> */
    public function samplings(): HasMany
    {
        return $this->hasMany(Sampling::class);
    }

    /** @return HasOne<Sampling, $this> */
    public function latestSampling(): HasOne
    {
        return $this->hasOne(Sampling::class)->ofMany(['sampled_on' => 'max', 'id' => 'max']);
    }

    /** @return HasMany<Treatment, $this> */
    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    /** @return HasMany<Harvest, $this> */
    public function harvests(): HasMany
    {
        return $this->hasMany(Harvest::class);
    }

    /**
     * Loads the aggregates metrics() needs in the same query.
     *
     * @param  Builder<self>  $query
     */
    public function scopeWithLogbook(Builder $query): void
    {
        $query->with(['species', 'latestSampling'])
            ->withSum('feedings', 'kilograms')
            ->withSum('samplings', 'mortality')
            ->withSum('harvests', 'fish_count')
            ->withSum('harvests', 'kilograms');
    }

    public function isOpen(): bool
    {
        return $this->closed_on === null;
    }

    public function metrics(): CycleMetrics
    {
        $asOf = $this->closed_on ?? CarbonImmutable::today();

        return new CycleMetrics(
            stockedFish: $this->stocked_fish,
            initialWeightGrams: (float) $this->initial_weight_grams,
            stockedOn: $this->stocked_on,
            asOf: $asOf->max($this->stocked_on),
            mortality: (int) $this->aggregate('samplings_sum_mortality', fn () => $this->samplings()->sum('mortality')),
            harvestedFish: (int) $this->aggregate('harvests_sum_fish_count', fn () => $this->harvests()->sum('fish_count')),
            harvestedKg: (float) $this->aggregate('harvests_sum_kilograms', fn () => $this->harvests()->sum('kilograms')),
            feedKg: (float) $this->aggregate('feedings_sum_kilograms', fn () => $this->feedings()->sum('kilograms')),
            sampledWeightGrams: $this->latestSampling?->averageWeightGrams(),
        );
    }

    public function stage(): GrowthStage
    {
        return $this->species->profile()->stageAt($this->metrics()->week());
    }

    public function weekOn(CarbonImmutable $date): int
    {
        return intdiv((int) $this->stocked_on->diffInDays($date), 7);
    }

    /**
     * @param  callable(): mixed  $query
     */
    private function aggregate(string $attribute, callable $query): mixed
    {
        if (array_key_exists($attribute, $this->getAttributes())) {
            return $this->getAttribute($attribute) ?? 0;
        }

        return $query();
    }
}
