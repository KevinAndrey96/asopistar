<?php

namespace App\Http\Controllers\Farmer;

use App\Actions\RecordHarvest;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeedingRequest;
use App\Http\Requests\HarvestRequest;
use App\Http\Requests\SamplingRequest;
use App\Http\Requests\TreatmentRequest;
use App\Models\Cycle;
use App\Models\FeedBrand;
use App\Models\Feeding;
use App\Models\Harvest;
use App\Models\Sampling;
use App\Models\Treatment;
use Illuminate\Http\RedirectResponse;

/**
 * Writes the daily logbook of an open cycle.
 */
final class LogbookController extends Controller
{
    public function storeFeeding(FeedingRequest $request, Cycle $cycle): RedirectResponse
    {
        $brand = FeedBrand::query()->findOrFail($request->integer('feed_brand_id'));
        $cycle->feedings()->create([
            ...$request->validated(),
            'protein_percent' => $brand->protein_percent,
        ]);

        return $this->back($cycle, 'alimentacion', 'Alimentación registrada.');
    }

    public function storeSampling(SamplingRequest $request, Cycle $cycle): RedirectResponse
    {
        $cycle->samplings()->create([...$request->validated(), 'mortality' => $request->integer('mortality')]);

        return $this->back($cycle, 'biometria', 'Biometría registrada.');
    }

    public function storeTreatment(TreatmentRequest $request, Cycle $cycle): RedirectResponse
    {
        $cycle->treatments()->create($request->validated());

        return $this->back($cycle, 'sanidad', 'Tratamiento registrado.');
    }

    public function storeHarvest(HarvestRequest $request, Cycle $cycle, RecordHarvest $recordHarvest): RedirectResponse
    {
        $recordHarvest($cycle, $request->harvest(), $request->boolean('close_cycle'));
        $closed = $cycle->refresh()->closed_on !== null;

        return $this->back($cycle, 'cosecha', $closed ? 'Cosecha registrada y ciclo cerrado.' : 'Cosecha parcial registrada.');
    }

    public function destroyFeeding(Cycle $cycle, Feeding $feeding): RedirectResponse
    {
        $feeding->delete();

        return $this->back($cycle, 'alimentacion', 'Registro de alimentación eliminado.');
    }

    public function destroySampling(Cycle $cycle, Sampling $sampling): RedirectResponse
    {
        $sampling->delete();

        return $this->back($cycle, 'biometria', 'Biometría eliminada.');
    }

    public function destroyTreatment(Cycle $cycle, Treatment $treatment): RedirectResponse
    {
        $treatment->delete();

        return $this->back($cycle, 'sanidad', 'Tratamiento eliminado.');
    }

    public function destroyHarvest(Cycle $cycle, Harvest $harvest): RedirectResponse
    {
        $harvest->delete();

        return $this->back($cycle, 'cosecha', 'Cosecha eliminada.');
    }

    private function back(Cycle $cycle, string $section, string $status): RedirectResponse
    {
        return redirect()->to(route('cycles.show', $cycle).'#'.$section)->with('status', $status);
    }
}
