<x-layouts.app :title="$farmer->fullName()">
    <x-page-header :title="$farmer->fullName()"
        :subtitle="'Cédula '.$farmer->document.($farmer->member_code ? ' · Asociado '.$farmer->member_code : '').($farmer->active ? '' : ' · Cuenta inactiva')"
        :back="[route('admin.farmers.index'), 'Piscicultores']">
        <x-slot:actions>
            <a href="{{ route('admin.farmers.edit', $farmer) }}" class="btn-secondary">Editar</a>
        </x-slot:actions>
    </x-page-header>

    <dl class="card mb-6 grid gap-4 p-5 text-sm sm:grid-cols-4">
        <div><dt class="text-slate-500">Correo</dt><dd class="font-medium">{{ $farmer->email }}</dd></div>
        <div><dt class="text-slate-500">Teléfono</dt><dd class="font-medium">{{ $farmer->phone ?? 'Sin dato' }}</dd></div>
        <div><dt class="text-slate-500">Predio</dt><dd class="font-medium">{{ $farmer->estate ?? 'Sin dato' }}</dd></div>
        <div><dt class="text-slate-500">Vereda</dt><dd class="font-medium">{{ $farmer->vereda ?? 'Sin dato' }}</dd></div>
    </dl>

    <h2 class="mb-3 text-lg font-semibold">Estanques y ciclos</h2>
    @forelse ($ponds as $pond)
        <article class="card mb-4 p-5">
            <div class="flex flex-wrap items-baseline justify-between gap-2">
                <h3 class="font-semibold"><a href="{{ route('ponds.show', $pond) }}" class="link">Estanque {{ $pond->code }}</a></h3>
                <p class="text-xs text-slate-500">{{ $fmt->number($pond->area_m2) }} m² · {{ $fmt->number($pond->water_flow_lps, 1) }} L/s{{ $pond->active ? '' : ' · Inactivo' }}</p>
            </div>
            @if ($pond->cycles->isEmpty())
                <p class="mt-2 text-sm text-slate-500">Sin siembras registradas.</p>
            @else
                <ul class="mt-3 divide-y divide-slate-100 text-sm">
                    @foreach ($pond->cycles as $cycle)
                        @php $m = $cycle->metrics(); @endphp
                        <li class="flex flex-wrap items-center justify-between gap-2 py-2">
                            <span>{{ $cycle->species->name }} · siembra del {{ $fmt->date($cycle->stocked_on) }} · {{ $fmt->number($m->population()) }} peces</span>
                            <span class="flex items-center gap-3">
                                @if ($cycle->isOpen())
                                    <x-stage :stage="$cycle->stage()" />
                                @else
                                    <span class="text-xs text-slate-500">Cerrado</span>
                                @endif
                                <a href="{{ route('cycles.show', $cycle) }}" class="link">Bitácora</a>
                                <a href="{{ route('cycles.report', $cycle) }}" class="link">PDF</a>
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </article>
    @empty
        <x-empty>Este piscicultor aún no ha registrado estanques.</x-empty>
    @endforelse
</x-layouts.app>
