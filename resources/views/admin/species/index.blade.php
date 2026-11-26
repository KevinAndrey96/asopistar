<x-layouts.app title="Especies">
    <x-page-header title="Especies" subtitle="Calendario de crecimiento en semanas desde la siembra; define la etapa y la cosecha proyectada.">
        <x-slot:actions><a href="{{ route('admin.species.create') }}" class="btn-primary">Registrar especie</a></x-slot:actions>
    </x-page-header>
    @if ($species->isEmpty())
        <x-empty>Aún no hay especies registradas.</x-empty>
    @else
        <div class="card overflow-x-auto">
            <table class="table-base table-stack">
                <thead><tr><th>Especie</th><th class="num">Cría hasta</th><th class="num">Levante hasta</th><th class="num">Cosecha</th><th class="num">Peso comercial</th><th class="num">Ciclos</th><th></th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($species as $item)
                        <tr>
                            <td data-label="Especie">{{ $item->name }} @if ($item->scientific_name)<span class="block text-xs text-slate-500 italic">{{ $item->scientific_name }}</span>@endif</td>
                            <td data-label="Cría hasta" class="num">Semana {{ $item->fry_until_week }}</td>
                            <td data-label="Levante hasta" class="num">Semana {{ $item->grow_out_until_week }}</td>
                            <td data-label="Cosecha" class="num">Semana {{ $item->harvest_week }}</td>
                            <td data-label="Peso comercial" class="num">{{ $fmt->grams($item->market_weight_grams) }}</td>
                            <td data-label="Ciclos" class="num">{{ $item->cycles_count }}</td>
                            <td class="space-x-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.species.edit', $item) }}" class="link">Editar</a>
                                @if ($item->cycles_count === 0)
                                    <x-delete-button :action="route('admin.species.destroy', $item)" :confirm="'¿Eliminar '.$item->name.'?'" />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.app>
