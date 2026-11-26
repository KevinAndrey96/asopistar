<?php

namespace App\Reports;

use App\Domain\Aquaculture\GrowthStage;
use App\Enums\Gender;
use App\Models\Cycle;
use App\Models\Harvest;
use App\Models\Pond;
use App\Models\User;
use Carbon\CarbonImmutable;

/**
 * Association-wide indicators built from every open cycle.
 */
final readonly class AssociationReport
{
    public const array HORIZONS = [4, 12, 24];

    /**
     * @param  array<string, int>  $farmersByGender
     * @param  array<string, int>  $cyclesByStage
     * @param  list<array{species: string, cycles: int, fish: int, biomassKg: float, projectedKg: float}>  $bySpecies
     * @param  array<int, float>  $projectedKgWithin
     * @param  list<array{farmer: User, ponds: int, cycles: int, fish: int, biomassKg: float}>  $byFarmer
     */
    public function __construct(
        public CarbonImmutable $generatedOn,
        public int $farmers,
        public array $farmersByGender,
        public int $activePonds,
        public int $openCycles,
        public int $fishInWater,
        public float $biomassKg,
        public float $feedKg,
        public float $harvestedKgThisYear,
        public array $cyclesByStage,
        public array $bySpecies,
        public array $projectedKgWithin,
        public array $byFarmer,
    ) {}

    public static function build(): self
    {
        $today = CarbonImmutable::today();
        $farmers = User::query()->farmers()->where('active', true)->orderBy('name')->get();
        $cycles = Cycle::query()->withLogbook()->with('pond')->whereNull('closed_on')
            ->whereHas('pond', fn ($query) => $query->where('active', true))
            ->get();

        $byGender = [];
        foreach (Gender::cases() as $gender) {
            $byGender[$gender->label()] = $farmers->where('gender', $gender)->count();
        }
        $byGender['Sin dato'] = $farmers->whereNull('gender')->count();

        $byStage = array_fill_keys(array_map(fn (GrowthStage $stage) => $stage->label(), GrowthStage::cases()), 0);
        $projected = array_fill_keys(self::HORIZONS, 0.0);
        $species = [];
        $perFarmer = [];
        $fish = 0;
        $biomass = 0.0;
        $feed = 0.0;

        foreach ($cycles as $cycle) {
            $metrics = $cycle->metrics();
            $profile = $cycle->species->profile();
            $byStage[$profile->stageAt($metrics->week())->label()]++;
            $fish += $metrics->population();
            $biomass += $metrics->biomassKg();
            $feed += $metrics->feedKg;
            foreach (self::HORIZONS as $weeks) {
                if ($profile->weeksToHarvest($metrics->week()) <= $weeks) {
                    $projected[$weeks] += $metrics->projectedHarvestKg($profile);
                }
            }

            $row = $species[$cycle->species->name] ?? ['species' => $cycle->species->name, 'cycles' => 0, 'fish' => 0, 'biomassKg' => 0.0, 'projectedKg' => 0.0];
            $row['cycles']++;
            $row['fish'] += $metrics->population();
            $row['biomassKg'] += $metrics->biomassKg();
            $row['projectedKg'] += $metrics->projectedHarvestKg($profile);
            $species[$cycle->species->name] = $row;

            $owner = $cycle->pond->user_id;
            $perFarmer[$owner]['cycles'] = ($perFarmer[$owner]['cycles'] ?? 0) + 1;
            $perFarmer[$owner]['fish'] = ($perFarmer[$owner]['fish'] ?? 0) + $metrics->population();
            $perFarmer[$owner]['biomassKg'] = ($perFarmer[$owner]['biomassKg'] ?? 0.0) + $metrics->biomassKg();
        }
        ksort($species);

        $pondCounts = Pond::query()->where('active', true)->selectRaw('user_id, count(*) as total')->groupBy('user_id')->pluck('total', 'user_id');
        $byFarmer = $farmers->map(fn (User $farmer) => [
            'farmer' => $farmer,
            'ponds' => (int) ($pondCounts[$farmer->id] ?? 0),
            'cycles' => $perFarmer[$farmer->id]['cycles'] ?? 0,
            'fish' => $perFarmer[$farmer->id]['fish'] ?? 0,
            'biomassKg' => $perFarmer[$farmer->id]['biomassKg'] ?? 0.0,
        ])->all();

        return new self(
            generatedOn: $today,
            farmers: $farmers->count(),
            farmersByGender: $byGender,
            activePonds: (int) $pondCounts->sum(),
            openCycles: $cycles->count(),
            fishInWater: $fish,
            biomassKg: $biomass,
            feedKg: $feed,
            harvestedKgThisYear: (float) Harvest::query()->whereYear('harvested_on', $today->year)->sum('kilograms'),
            cyclesByStage: $byStage,
            bySpecies: array_values($species),
            projectedKgWithin: $projected,
            byFarmer: array_values($byFarmer),
        );
    }
}
