<?php

namespace Tests\Unit\Domain;

use App\Domain\Aquaculture\GrowthStage;
use App\Domain\Aquaculture\SpeciesProfile;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SpeciesProfileTest extends TestCase
{
    /** @return iterable<string, array{int, GrowthStage}> */
    public static function weeks(): iterable
    {
        yield 'stocking week' => [0, GrowthStage::Fry];
        yield 'last fry week' => [4, GrowthStage::Fry];
        yield 'first grow-out week' => [5, GrowthStage::GrowOut];
        yield 'last grow-out week' => [12, GrowthStage::GrowOut];
        yield 'fattening' => [13, GrowthStage::Fattening];
        yield 'past harvest' => [40, GrowthStage::Fattening];
    }

    #[DataProvider('weeks')]
    public function test_stage_follows_the_species_calendar(int $week, GrowthStage $expected): void
    {
        $this->assertSame($expected, $this->tilapia()->stageAt($week));
    }

    public function test_weeks_to_harvest_never_goes_negative(): void
    {
        $this->assertSame(14, $this->tilapia()->weeksToHarvest(10));
        $this->assertSame(0, $this->tilapia()->weeksToHarvest(30));
    }

    /** @return iterable<string, array{int, int, int, int}> */
    public static function invalidCalendars(): iterable
    {
        yield 'grow-out before fry' => [8, 6, 24, 450];
        yield 'harvest inside grow-out' => [4, 12, 12, 450];
        yield 'no fry stage' => [0, 12, 24, 450];
        yield 'zero market weight' => [4, 12, 24, 0];
    }

    #[DataProvider('invalidCalendars')]
    public function test_rejects_inconsistent_calendars(int $fry, int $growOut, int $harvest, int $weight): void
    {
        $this->expectException(InvalidArgumentException::class);

        new SpeciesProfile($fry, $growOut, $harvest, $weight);
    }

    private function tilapia(): SpeciesProfile
    {
        return new SpeciesProfile(fryUntilWeek: 4, growOutUntilWeek: 12, harvestWeek: 24, marketWeightGrams: 450);
    }
}
