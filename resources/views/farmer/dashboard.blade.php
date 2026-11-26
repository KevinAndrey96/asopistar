<x-layouts.app title="Panel">
    <x-page-header :title="'Hola, '.auth()->user()->name" subtitle="Estado de tus estanques hoy.">
        <x-slot:actions>
            <a href="{{ route('ponds.create') }}" class="btn-primary">Registrar estanque</a>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <x-stat label="Estanques activos" :value="$totals['ponds']" />
        <x-stat label="Ciclos en curso" :value="$totals['cycles']" :hint="$idle > 0 ? $idle.' sin sembrar' : null" />
        <x-stat label="Peces en agua" :value="$fmt->number($totals['fish'])" hint="Siembra menos mortalidad y cosechas" />
        <x-stat label="Biomasa estimada" :value="$fmt->kg($totals['biomassKg'])" hint="Según la última biometría" />
    </div>

    <h2 class="mt-8 mb-3 text-lg font-semibold">Estanques</h2>
    @if ($ponds->isEmpty())
        <x-empty>
            Aún no tienes estanques. <a href="{{ route('ponds.create') }}" class="link">Registra el primero</a> para empezar la bitácora.
        </x-empty>
    @else
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($ponds as $pond)
                @php $cycle = $pond->openCycle; @endphp
                <article class="card flex flex-col p-5">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="font-semibold"><a href="{{ route('ponds.show', $pond) }}" class="hover:underline">Estanque {{ $pond->code }}</a></h3>
                            <p class="text-xs text-slate-500">{{ $fmt->number($pond->area_m2) }} m² · {{ $fmt->number($pond->water_flow_lps, 1) }} L/s</p>
                        </div>
                        @if ($cycle)
                            <x-stage :stage="$cycle->stage()" />
                        @endif
                    </div>
                    @if ($cycle)
                        @php $m = $cycle->metrics(); $profile = $cycle->species->profile(); @endphp
                        <dl class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                            <dt class="text-slate-500">Especie</dt><dd class="text-right">{{ $cycle->species->name }}</dd>
                            <dt class="text-slate-500">Semana</dt><dd class="text-right tabular-nums">{{ $m->week() }}</dd>
                            <dt class="text-slate-500">Peces</dt><dd class="text-right tabular-nums">{{ $fmt->number($m->population()) }}</dd>
                            <dt class="text-slate-500">Peso promedio</dt><dd class="text-right tabular-nums">{{ $fmt->grams($m->averageWeightGrams()) }}</dd>
                            <dt class="text-slate-500">Cosecha</dt>
                            <dd class="text-right">{{ $profile->weeksToHarvest($m->week()) === 0 ? 'Lista' : 'En '.$profile->weeksToHarvest($m->week()).' semanas' }}</dd>
                        </dl>
                        <a href="{{ route('cycles.show', $cycle) }}" class="btn-secondary mt-4">Abrir bitácora</a>
                    @else
                        <p class="mt-4 text-sm text-slate-600">Sin siembra en curso.</p>
                        <a href="{{ route('cycles.create', $pond) }}" class="btn-primary mt-4">Registrar siembra</a>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</x-layouts.app>
