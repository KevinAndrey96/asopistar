<x-layouts.app title="Estanques">
    <x-page-header title="Estanques" subtitle="Infraestructura de tu predio y su ciclo productivo actual.">
        <x-slot:actions>
            <a href="{{ route('ponds.create') }}" class="btn-primary">Registrar estanque</a>
        </x-slot:actions>
    </x-page-header>

    @if ($ponds->isEmpty())
        <x-empty>Aún no tienes estanques registrados.</x-empty>
    @else
        <div class="card overflow-x-auto">
            <table class="table-base table-stack">
                <thead>
                    <tr><th>Código</th><th class="num">Espejo de agua</th><th class="num">Caudal</th><th>Ciclo actual</th><th class="num">Ciclos</th><th>Estado</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($ponds as $pond)
                        <tr>
                            <td data-label="Código"><a href="{{ route('ponds.show', $pond) }}" class="link">{{ $pond->code }}</a></td>
                            <td data-label="Espejo de agua" class="num">{{ $fmt->number($pond->area_m2) }} m²</td>
                            <td data-label="Caudal" class="num">{{ $fmt->number($pond->water_flow_lps, 1) }} L/s</td>
                            <td data-label="Ciclo actual">
                                @if ($pond->openCycle)
                                    {{ $pond->openCycle->species->name }}, semana {{ $pond->openCycle->metrics()->week() }}
                                @else
                                    <span class="text-slate-400">Sin siembra</span>
                                @endif
                            </td>
                            <td data-label="Ciclos" class="num">{{ $pond->cycles_count }}</td>
                            <td data-label="Estado">{{ $pond->active ? 'Activo' : 'Inactivo' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.app>
