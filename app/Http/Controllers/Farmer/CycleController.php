<?php

namespace App\Http\Controllers\Farmer;

use App\Actions\CloseCycle;
use App\Actions\StartCycle;
use App\Http\Controllers\Controller;
use App\Http\Requests\CloseCycleRequest;
use App\Http\Requests\StartCycleRequest;
use App\Models\Cycle;
use App\Models\FeedBrand;
use App\Models\Pond;
use App\Models\Provider;
use App\Models\Species;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

final class CycleController extends Controller
{
    public function create(Pond $pond): View|RedirectResponse
    {
        if ($pond->openCycle !== null) {
            return redirect()->route('cycles.show', $pond->openCycle)->with('status', 'Este estanque ya tiene un ciclo abierto.');
        }

        return view('farmer.cycles.create', [
            'pond' => $pond,
            'species' => Species::query()->orderBy('name')->get(),
            'providers' => Provider::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StartCycleRequest $request, Pond $pond, StartCycle $startCycle): RedirectResponse
    {
        $cycle = $startCycle($pond, $request->cycle());

        return redirect()->route('cycles.show', $cycle)->with('status', 'Siembra registrada. El ciclo está abierto.');
    }

    public function show(Cycle $cycle): View
    {
        return view('farmer.cycles.show', [
            ...$this->logbook($cycle),
            'feedBrands' => FeedBrand::query()->orderBy('name')->get(),
            'today' => CarbonImmutable::today()->toDateString(),
        ]);
    }

    public function close(CloseCycleRequest $request, Cycle $cycle, CloseCycle $closeCycle): RedirectResponse
    {
        $closeCycle($cycle, $request->string('closed_on')->toString());

        return redirect()->route('cycles.show', $cycle)->with('status', 'Ciclo cerrado.');
    }

    public function report(Cycle $cycle): Response
    {
        $data = $this->logbook($cycle);
        $name = Str::slug('reporte estanque '.$cycle->pond->code.' ciclo '.$cycle->stocked_on->format('Y-m-d'));

        return Pdf::loadView('reports.cycle', $data)->setPaper('letter')->stream($name.'.pdf');
    }

    /**
     * @return array<string, mixed>
     */
    private function logbook(Cycle $cycle): array
    {
        $cycle->load(['pond.user', 'species', 'provider', 'latestSampling'])
            ->loadSum('feedings', 'kilograms')
            ->loadSum('samplings', 'mortality')
            ->loadSum('harvests', 'fish_count')
            ->loadSum('harvests', 'kilograms');

        return [
            'cycle' => $cycle,
            'metrics' => $cycle->metrics(),
            'profile' => $cycle->species->profile(),
            'feedings' => $cycle->feedings()->with('feedBrand')->latest('fed_on')->latest('id')->get(),
            'samplings' => $cycle->samplings()->latest('sampled_on')->latest('id')->get(),
            'treatments' => $cycle->treatments()->latest('applied_on')->latest('id')->get(),
            'harvests' => $cycle->harvests()->latest('harvested_on')->latest('id')->get(),
        ];
    }
}
