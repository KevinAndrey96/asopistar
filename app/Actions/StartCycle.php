<?php

namespace App\Actions;

use App\Models\Cycle;
use App\Models\Pond;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Registers the stocking of fingerlings, which opens a production cycle.
 */
final class StartCycle
{
    /**
     * @param  array{species_id: int, provider_id: int|null, stocked_fish: int, initial_weight_grams: float|string, stocked_on: string}  $data
     */
    public function __invoke(Pond $pond, array $data): Cycle
    {
        return DB::transaction(function () use ($pond, $data): Cycle {
            Pond::query()->whereKey($pond->id)->lockForUpdate()->first();
            if ($pond->cycles()->whereNull('closed_on')->exists()) {
                throw ValidationException::withMessages([
                    'stocked_on' => 'El estanque ya tiene un ciclo abierto. Ciérralo antes de sembrar de nuevo.',
                ]);
            }
            $latestClose = substr((string) $pond->cycles()->max('closed_on'), 0, 10);
            if ($latestClose !== '' && $data['stocked_on'] < $latestClose) {
                throw ValidationException::withMessages([
                    'stocked_on' => 'La siembra no puede ser anterior al cierre del ciclo previo ('.$latestClose.').',
                ]);
            }

            return $pond->cycles()->create($data);
        });
    }
}
