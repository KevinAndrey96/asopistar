<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Pond;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        /** @var User $farmer */
        $farmer = $request->user();
        $ponds = $farmer->ponds()
            ->where('active', true)
            ->with(['openCycle' => fn ($query) => $query->withLogbook()])
            ->orderBy('code')
            ->get();

        $open = $ponds->pluck('openCycle')->filter();

        return view('farmer.dashboard', [
            'ponds' => $ponds,
            'totals' => [
                'ponds' => $ponds->count(),
                'cycles' => $open->count(),
                'fish' => $open->sum(fn ($cycle) => $cycle->metrics()->population()),
                'biomassKg' => $open->sum(fn ($cycle) => $cycle->metrics()->biomassKg()),
            ],
            'idle' => $ponds->filter(fn (Pond $pond) => $pond->openCycle === null)->count(),
        ]);
    }
}
