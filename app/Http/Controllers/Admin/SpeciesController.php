<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SpeciesRequest;
use App\Models\Species;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class SpeciesController extends Controller
{
    public function index(): View
    {
        return view('admin.species.index', ['species' => Species::query()->withCount('cycles')->orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.species.form', ['species' => new Species]);
    }

    public function store(SpeciesRequest $request): RedirectResponse
    {
        Species::query()->create($request->validated());

        return redirect()->route('admin.species.index')->with('status', 'Especie registrada.');
    }

    public function edit(Species $species): View
    {
        return view('admin.species.form', ['species' => $species]);
    }

    public function update(SpeciesRequest $request, Species $species): RedirectResponse
    {
        $species->update($request->validated());

        return redirect()->route('admin.species.index')->with('status', 'Especie actualizada.');
    }

    public function destroy(Species $species): RedirectResponse
    {
        if ($species->cycles()->exists()) {
            return back()->withErrors(['delete' => 'No se puede eliminar: hay ciclos con '.$species->name.'.']);
        }
        $species->delete();

        return redirect()->route('admin.species.index')->with('status', 'Especie eliminada.');
    }
}
