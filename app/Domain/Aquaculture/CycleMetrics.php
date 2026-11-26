<?php

namespace App\Domain\Aquaculture;

use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Production indicators of one stocking, computed from its logbook totals.
 */
final readonly class CycleMetrics
{
    public function __construct(
        public int $stockedFish,
        public float $initialWeightGrams,
        public DateTimeImmutable $stockedOn,
        public DateTimeImmutable $asOf,
        public int $mortality = 0,
        public int $harvestedFish = 0,
        public float $harvestedKg = 0.0,
        public float $feedKg = 0.0,
        public ?float $sampledWeightGrams = null,
    ) {
        if ($stockedFish < 1) {
            throw new InvalidArgumentException('a cycle needs at least one fish');
        }
        if ($asOf < $stockedOn) {
            throw new InvalidArgumentException('metrics date precedes the stocking');
        }
    }

    public function week(): int
    {
        return intdiv((int) $this->stockedOn->diff($this->asOf)->days, 7);
    }

    public function population(): int
    {
        return max(0, $this->stockedFish - $this->mortality - $this->harvestedFish);
    }

    public function averageWeightGrams(): float
    {
        return $this->sampledWeightGrams ?? $this->initialWeightGrams;
    }

    public function biomassKg(): float
    {
        return $this->population() * $this->averageWeightGrams() / 1000;
    }

    public function survivalRate(): float
    {
        return max(0, $this->stockedFish - $this->mortality) / $this->stockedFish;
    }

    /**
     * Feed conversion ratio: kilograms of feed per kilogram of weight gained,
     * counting fish already harvested. Null until the fish have gained weight.
     */
    public function feedConversionRatio(): ?float
    {
        $initialKg = $this->stockedFish * $this->initialWeightGrams / 1000;
        $gainKg = $this->biomassKg() + $this->harvestedKg - $initialKg;
        if ($gainKg <= 0 || $this->feedKg <= 0) {
            return null;
        }

        return $this->feedKg / $gainKg;
    }

    public function projectedHarvestKg(SpeciesProfile $species): float
    {
        return $this->population() * $species->marketWeightGrams / 1000;
    }
}
