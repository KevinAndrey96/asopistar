<x-layouts.app title="Piscicultores">
    <x-page-header title="Piscicultores" subtitle="Asociados con acceso a la bitácora.">
        <x-slot:actions>
            <a href="{{ route('admin.farmers.create') }}" class="btn-primary">Registrar piscicultor</a>
        </x-slot:actions>
    </x-page-header>

    <form method="get" class="mb-4 flex max-w-md gap-2" role="search">
        <label for="q" class="sr-only">Buscar</label>
        <input id="q" name="q" value="{{ $search }}" placeholder="Nombre, cédula o vereda" class="input">
        <button class="btn-secondary">Buscar</button>
    </form>

    @if ($farmers->isEmpty())
        <x-empty>{{ $search === '' ? 'Aún no hay piscicultores registrados.' : 'Ningún piscicultor coincide con la búsqueda.' }}</x-empty>
    @else
        <div class="card overflow-x-auto">
            <table class="table-base table-stack">
                <thead><tr><th>Nombre</th><th>Cédula</th><th>Predio y vereda</th><th>Teléfono</th><th class="num">Estanques</th><th>Estado</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($farmers as $farmer)
                        <tr>
                            <td data-label="Nombre"><a href="{{ route('admin.farmers.show', $farmer) }}" class="link">{{ $farmer->fullName() }}</a></td>
                            <td data-label="Cédula">{{ $farmer->document }}</td>
                            <td data-label="Predio y vereda">{{ collect([$farmer->estate, $farmer->vereda])->filter()->implode(', ') }}</td>
                            <td data-label="Teléfono">{{ $farmer->phone }}</td>
                            <td data-label="Estanques" class="num">{{ $farmer->ponds_count }}</td>
                            <td data-label="Estado">{{ $farmer->active ? 'Activo' : 'Inactivo' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $farmers->links() }}
    @endif
</x-layouts.app>
