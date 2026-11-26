<?php

namespace Tests\Unit\Domain;

use App\Domain\Aquaculture\CycleMetrics;
use App\Domain\Aquaculture\SpeciesProfile;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CycleMetricsTest extends TestCase
{
    public function test_week_counts_full_weeks_since_stocking(): void
    {
        $this->assertSame(0, $this->metrics(asOf: '2026-03-07')->week());
        $this->assertSame(1, $this->metrics(asOf: '2026-03-08')->week());
        $this->assertSame(10, $this->metrics(asOf: '2026-05-12')->week());
    }

    public function test_population_discounts_mortality_and_harvests(): void
    {
        $metrics = $this->metrics(mortality: 50, harvestedFish: 300);

        $this->assertSame(650, $metrics->population());
        $this->assertEqualsWithDelta(0.95, $metrics->survivalRate(), 1e-9);
    }

    public function test_population_never_goes_negative(): void
    {
        $this->assertSame(0, $this->metrics(mortality: 600, harvestedFish: 600)->population());
    }

    public function test_biomass_uses_the_latest_sampling_or_the_stocking_weight(): void
    {
        $this->assertEqualsWithDelta(2.0, $this->metrics()->biomassKg(), 1e-9);
        $this->assertEqualsWithDelta(237.5, $this->metrics(mortality: 50, sampled: 250)->biomassKg(), 1e-9);
    }

    public function test_feed_conversion_ratio_counts_harvested_weight(): void
    {
        // 700 fish at 300 g (210 kg) plus 90 kg harvested, minus 2 kg stocked: 298 kg gained.
        $metrics = $this->metrics(harvestedFish: 300, harvestedKg: 90, feedKg: 447, sampled: 300);

        $this->assertEqualsWithDelta(1.5, $metrics->feedConversionRatio(), 1e-9);
    }

    public function test_feed_conversion_ratio_is_undefined_without_weight_gain_or_feed(): void
    {
        $this->assertNull($this->metrics(feedKg: 30)->feedConversionRatio());
        $this->assertNull($this->metrics(sampled: 100)->feedConversionRatio());
    }

    public function test_projected_harvest_uses_the_market_weight_of_the_species(): void
    {
        $profile = new SpeciesProfile(4, 12, 24, 450);

        $this->assertEqualsWithDelta(427.5, $this->metrics(mortality: 50)->projectedHarvestKg($profile), 1e-9);
    }

    public function test_rejects_impossible_cycles(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->metrics(asOf: '2026-02-01');
    }

    private function metrics(
        string $asOf = '2026-05-12',
        int $mortality = 0,
        int $harvestedFish = 0,
        float $harvestedKg = 0,
        float $feedKg = 0,
        ?float $sampled = null,
    ): CycleMetrics {
        return new CycleMetrics(
            stockedFish: 1000,
            initialWeightGrams: 2,
            stockedOn: new DateTimeImmutable('2026-03-01'),
            asOf: new DateTimeImmutable($asOf),
            mortality: $mortality,
            harvestedFish: $harvestedFish,
            harvestedKg: $harvestedKg,
            feedKg: $feedKg,
            sampledWeightGrams: $sampled,
        );
    }
}
