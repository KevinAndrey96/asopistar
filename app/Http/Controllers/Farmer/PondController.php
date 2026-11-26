<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Http\Requests\PondRequest;
use App\Models\Pond;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class PondController extends Controller
{
    public function index(Request $request): View
    {
        /** @var User $farmer */
        $farmer = $request->user();

        return view('farmer.ponds.index', [
            'ponds' => $farmer->ponds()
                ->with(['openCycle' => fn ($query) => $query->withLogbook()])
                ->withCount('cycles')
                ->orderByDesc('active')->orderBy('code')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('farmer.ponds.form', ['pond' => new Pond(['active' => true])]);
    }

    public function store(PondRequest $request): RedirectResponse
    {
        /** @var User $farmer */
        $farmer = $request->user();
        $pond = $farmer->ponds()->create([...$request->validated(), 'active' => true]);

        return redirect()->route('ponds.show', $pond)->with('status', 'Estanque '.$pond->code.' registrado.');
    }

    public function show(Pond $pond): View
    {
        return view('farmer.ponds.show', [
            'pond' => $pond->load('user'),
            'cycles' => $pond->cycles()->withLogbook()->with('provider')->latest('stocked_on')->latest('id')->get(),
        ]);
    }

    public function edit(Pond $pond): View
    {
        return view('farmer.ponds.form', ['pond' => $pond]);
    }

    public function update(PondRequest $request, Pond $pond): RedirectResponse
    {
        $data = [...$request->validated(), 'active' => $request->boolean('active')];
        if (! $data['active'] && $pond->openCycle !== null) {
            return back()->withInput()->withErrors(['active' => 'Cierra el ciclo abierto antes de desactivar el estanque.']);
        }
        $pond->update($data);

        return redirect()->route('ponds.show', $pond)->with('status', 'Estanque actualizado.');
    }
}
