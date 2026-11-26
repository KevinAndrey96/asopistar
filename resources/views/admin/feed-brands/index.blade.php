<x-layouts.app title="Alimentos">
    <x-page-header title="Alimentos" subtitle="Concentrados que los piscicultores pueden registrar en la bitácora.">
        <x-slot:actions><a href="{{ route('admin.feed-brands.create') }}" class="btn-primary">Registrar alimento</a></x-slot:actions>
    </x-page-header>
    @if ($brands->isEmpty())
        <x-empty>Aún no hay alimentos registrados.</x-empty>
    @else
        <div class="card overflow-x-auto">
            <table class="table-base table-stack">
                <thead><tr><th>Alimento</th><th class="num">Proteína</th><th class="num">Registros</th><th></th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($brands as $brand)
                        <tr>
                            <td data-label="Alimento">{{ $brand->name }}</td>
                            <td data-label="Proteína" class="num">{{ $fmt->number($brand->protein_percent, 1) }} %</td>
                            <td data-label="Registros" class="num">{{ $brand->feedings_count }}</td>
                            <td class="space-x-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.feed-brands.edit', $brand) }}" class="link">Editar</a>
                                @if ($brand->feedings_count === 0)
                                    <x-delete-button :action="route('admin.feed-brands.destroy', $brand)" :confirm="'¿Eliminar '.$brand->name.'?'" />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.app>
