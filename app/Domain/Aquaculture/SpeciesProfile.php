<?php

namespace App\Domain\Aquaculture;

use InvalidArgumentException;

/**
 * Growth calendar of a farmed species, measured in weeks since stocking.
 */
final readonly class SpeciesProfile
{
    public function __construct(
        public int $fryUntilWeek,
        public int $growOutUntilWeek,
        public int $harvestWeek,
        public int $marketWeightGrams,
    ) {
        if ($fryUntilWeek < 1 || $growOutUntilWeek <= $fryUntilWeek) {
            throw new InvalidArgumentException('grow-out must end after the fry stage');
        }
        if ($harvestWeek <= $growOutUntilWeek) {
            throw new InvalidArgumentException('harvest week must fall in the fattening stage');
        }
        if ($marketWeightGrams < 1) {
            throw new InvalidArgumentException('market weight must be positive');
        }
    }

    public function stageAt(int $week): GrowthStage
    {
        return match (true) {
            $week <= $this->fryUntilWeek => GrowthStage::Fry,
            $week <= $this->growOutUntilWeek => GrowthStage::GrowOut,
            default => GrowthStage::Fattening,
        };
    }

    public function weeksToHarvest(int $week): int
    {
        return max(0, $this->harvestWeek - $week);
    }
}
