@php $owner = auth()->id() === $pond->user_id; @endphp
<x-layouts.app :title="'Estanque '.$pond->code">
    <x-page-header :title="'Estanque '.$pond->code"
        :subtitle="$fmt->number($pond->area_m2).' m² de espejo de agua · '.$fmt->number($pond->water_flow_lps, 1).' L/s'.($pond->active ? '' : ' · Inactivo')"
        :back="$owner ? [route('ponds.index'), 'Estanques'] : [route('admin.farmers.show', $pond->user_id), $pond->user->fullName()]">
        @if ($owner)
            <x-slot:actions>
                <a href="{{ route('ponds.edit', $pond) }}" class="btn-secondary">Editar</a>
                @if ($pond->active && $cycles->first(fn ($cycle) => $cycle->isOpen()) === null)
                    <a href="{{ route('cycles.create', $pond) }}" class="btn-primary">Registrar siembra</a>
                @endif
            </x-slot:actions>
        @endif
    </x-page-header>

    @if ($pond->equipment)
        <p class="mb-6 text-sm text-slate-600"><span class="font-medium text-slate-800">Equipos:</span> {{ $pond->equipment }}</p>
    @endif

    <h2 class="mb-3 text-lg font-semibold">Ciclos productivos</h2>
    @if ($cycles->isEmpty())
        <x-empty>Este estanque todavía no tiene siembras registradas.</x-empty>
    @else
        <div class="card overflow-x-auto">
            <table class="table-base table-stack">
                <thead>
                    <tr><th>Siembra</th><th>Especie</th><th class="num">Alevinos</th><th class="num">Peces hoy</th><th class="num">Cosechado</th><th class="num">FCR</th><th>Estado</th><th></th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($cycles as $cycle)
                        @php $m = $cycle->metrics(); @endphp
                        <tr>
                            <td data-label="Siembra">{{ $fmt->date($cycle->stocked_on) }}</td>
                            <td data-label="Especie">{{ $cycle->species->name }}</td>
                            <td data-label="Alevinos" class="num">{{ $fmt->number($cycle->stocked_fish) }}</td>
                            <td data-label="Peces hoy" class="num">{{ $fmt->number($m->population()) }}</td>
                            <td data-label="Cosechado" class="num">{{ $fmt->kg($m->harvestedKg) }}</td>
                            <td data-label="FCR" class="num">{{ $fmt->ratio($m->feedConversionRatio()) }}</td>
                            <td data-label="Estado">
                                @if ($cycle->isOpen())
                                    <x-stage :stage="$cycle->stage()" />
                                @else
                                    Cerrado el {{ $fmt->date($cycle->closed_on) }}
                                @endif
                            </td>
                            <td class="whitespace-nowrap text-right">
                                <a href="{{ route('cycles.show', $cycle) }}" class="link">Bitácora</a>
                                <a href="{{ route('cycles.report', $cycle) }}" class="link ml-3">PDF</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.app>
