<?php

namespace App\Actions;

use App\Models\Cycle;
use Illuminate\Validation\ValidationException;

/**
 * Closes a cycle without a final harvest, for example after a total loss.
 */
final class CloseCycle
{
    public function __invoke(Cycle $cycle, string $closedOn): void
    {
        $lastEntry = collect([
            $cycle->stocked_on->toDateString(),
            $cycle->feedings()->max('fed_on'),
            $cycle->samplings()->max('sampled_on'),
            $cycle->treatments()->max('applied_on'),
            $cycle->harvests()->max('harvested_on'),
        ])->filter()->map(fn (mixed $date): string => substr((string) $date, 0, 10))->max();

        if ($closedOn < $lastEntry) {
            throw ValidationException::withMessages([
                'closed_on' => 'El cierre no puede ser anterior al último registro de la bitácora ('.$lastEntry.').',
            ])->errorBag('close');
        }

        $cycle->update(['closed_on' => $closedOn]);
    }
}
