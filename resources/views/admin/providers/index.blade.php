<x-layouts.app title="Proveedores">
    <x-page-header title="Proveedores de alevinos" subtitle="Origen de la semilla de cada siembra.">
        <x-slot:actions><a href="{{ route('admin.providers.create') }}" class="btn-primary">Registrar proveedor</a></x-slot:actions>
    </x-page-header>
    @if ($providers->isEmpty())
        <x-empty>Aún no hay proveedores registrados.</x-empty>
    @else
        <div class="card overflow-x-auto">
            <table class="table-base table-stack">
                <thead><tr><th>Proveedor</th><th>NIT</th><th>Ciudad</th><th>Contacto</th><th class="num">Siembras</th><th></th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($providers as $provider)
                        <tr>
                            <td data-label="Proveedor">{{ $provider->name }}</td>
                            <td data-label="NIT" class="tabular-nums">{{ $provider->formattedNit() }}</td>
                            <td data-label="Ciudad">{{ $provider->city }}</td>
                            <td data-label="Contacto">{{ collect([$provider->contact_name, $provider->phone])->filter()->implode(' · ') }}</td>
                            <td data-label="Siembras" class="num">{{ $provider->cycles_count }}</td>
                            <td class="space-x-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.providers.edit', $provider) }}" class="link">Editar</a>
                                <x-delete-button :action="route('admin.providers.destroy', $provider)" :confirm="'¿Eliminar '.$provider->name.'? Las siembras quedarán sin proveedor.'" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.app>
