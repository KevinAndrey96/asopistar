<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Gender;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FarmerRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class FarmerController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->trim()->limit(60, '')->toString();

        return view('admin.farmers.index', [
            'search' => $search,
            'farmers' => User::query()->farmers()
                ->when($search !== '', fn ($query) => $query->where(fn ($query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('document', 'like', "%{$search}%")
                    ->orWhere('vereda', 'like', "%{$search}%")))
                ->withCount(['ponds' => fn ($query) => $query->where('active', true)])
                ->orderByDesc('active')->orderBy('name')->orderBy('last_name')
                ->paginate(20)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.farmers.form', ['farmer' => new User(['active' => true]), 'genders' => Gender::cases()]);
    }

    public function store(FarmerRequest $request): RedirectResponse
    {
        $farmer = User::query()->create([...$request->farmer(), 'role' => Role::Farmer]);

        return redirect()->route('admin.farmers.show', $farmer)->with('status', 'Piscicultor '.$farmer->fullName().' registrado.');
    }

    public function show(User $farmer): View
    {
        return view('admin.farmers.show', [
            'farmer' => $farmer,
            'ponds' => $farmer->ponds()
                ->with(['cycles' => fn ($query) => $query->withLogbook()->latest('stocked_on')])
                ->orderByDesc('active')->orderBy('code')
                ->get(),
        ]);
    }

    public function edit(User $farmer): View
    {
        return view('admin.farmers.form', ['farmer' => $farmer, 'genders' => Gender::cases()]);
    }

    public function update(FarmerRequest $request, User $farmer): RedirectResponse
    {
        $farmer->update($request->farmer());

        return redirect()->route('admin.farmers.show', $farmer)->with('status', 'Datos actualizados.');
    }
}
