<?php

namespace App\Actions;

use App\Models\Cycle;
use App\Models\Harvest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Records a total or partial harvest; the cycle closes when asked to or
 * when no fish remain in the pond.
 */
final class RecordHarvest
{
    /**
     * @param  array{harvested_on: string, fish_count: int, kilograms: float|string, destination: string, ice_slaughter_kg?: float|string|null, ice_cooling_kg?: float|string|null, ice_transport_kg?: float|string|null}  $data
     */
    public function __invoke(Cycle $cycle, array $data, bool $closeCycle): Harvest
    {
        return DB::transaction(function () use ($cycle, $data, $closeCycle): Harvest {
            $locked = Cycle::query()->whereKey($cycle->id)->lockForUpdate()->firstOrFail();
            $population = $locked->metrics()->population();
            if ($data['fish_count'] > $population) {
                throw ValidationException::withMessages([
                    'fish_count' => 'Según la bitácora quedan '.$population.' peces en el estanque.',
                ])->errorBag('harvest');
            }

            $harvest = $locked->harvests()->create([
                ...$data,
                'ice_slaughter_kg' => $data['ice_slaughter_kg'] ?? 0,
                'ice_cooling_kg' => $data['ice_cooling_kg'] ?? 0,
                'ice_transport_kg' => $data['ice_transport_kg'] ?? 0,
            ]);
            if ($closeCycle || $data['fish_count'] === $population) {
                $locked->update(['closed_on' => $data['harvested_on']]);
            }

            return $harvest;
        });
    }
}
