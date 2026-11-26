<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeedBrandRequest;
use App\Models\FeedBrand;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class FeedBrandController extends Controller
{
    public function index(): View
    {
        return view('admin.feed-brands.index', ['brands' => FeedBrand::query()->withCount('feedings')->orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.feed-brands.form', ['brand' => new FeedBrand]);
    }

    public function store(FeedBrandRequest $request): RedirectResponse
    {
        FeedBrand::query()->create($request->validated());

        return redirect()->route('admin.feed-brands.index')->with('status', 'Alimento registrado.');
    }

    public function edit(FeedBrand $feedBrand): View
    {
        return view('admin.feed-brands.form', ['brand' => $feedBrand]);
    }

    public function update(FeedBrandRequest $request, FeedBrand $feedBrand): RedirectResponse
    {
        $feedBrand->update($request->validated());

        return redirect()->route('admin.feed-brands.index')->with('status', 'Alimento actualizado.');
    }

    public function destroy(FeedBrand $feedBrand): RedirectResponse
    {
        if ($feedBrand->feedings()->exists()) {
            return back()->withErrors(['delete' => 'No se puede eliminar: hay alimentaciones registradas con '.$feedBrand->name.'.']);
        }
        $feedBrand->delete();

        return redirect()->route('admin.feed-brands.index')->with('status', 'Alimento eliminado.');
    }
}
