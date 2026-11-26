<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProviderRequest;
use App\Models\Provider;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class ProviderController extends Controller
{
    public function index(): View
    {
        return view('admin.providers.index', ['providers' => Provider::query()->withCount('cycles')->orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.providers.form', ['provider' => new Provider]);
    }

    public function store(ProviderRequest $request): RedirectResponse
    {
        Provider::query()->create($request->provider());

        return redirect()->route('admin.providers.index')->with('status', 'Proveedor registrado.');
    }

    public function edit(Provider $provider): View
    {
        return view('admin.providers.form', ['provider' => $provider]);
    }

    public function update(ProviderRequest $request, Provider $provider): RedirectResponse
    {
        $provider->update($request->provider());

        return redirect()->route('admin.providers.index')->with('status', 'Proveedor actualizado.');
    }

    public function destroy(Provider $provider): RedirectResponse
    {
        $provider->delete();

        return redirect()->route('admin.providers.index')->with('status', 'Proveedor eliminado. Los ciclos que lo citaban quedan sin proveedor.');
    }
}
