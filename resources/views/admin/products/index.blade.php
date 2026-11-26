<x-layouts.app title="Productos">
    <x-page-header title="Productos" subtitle="Catálogo del sitio público.">
        <x-slot:actions><a href="{{ route('admin.products.create') }}" class="btn-primary">Nuevo producto</a></x-slot:actions>
    </x-page-header>
    @if ($products->isEmpty())
        <x-empty>Aún no hay productos.</x-empty>
    @else
        <div class="card overflow-x-auto">
            <table class="table-base table-stack">
                <thead><tr><th>Producto</th><th class="num">Precio</th><th>Estado</th><th></th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($products as $product)
                        <tr>
                            <td data-label="Producto">{{ $product->name }}</td>
                            <td data-label="Precio" class="num">{{ $fmt->cop($product->price_cop) }} / {{ $product->unit }}</td>
                            <td data-label="Estado">{{ $product->available ? 'Disponible' : 'Oculto' }}</td>
                            <td class="space-x-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.products.edit', $product) }}" class="link">Editar</a>
                                <x-delete-button :action="route('admin.products.destroy', $product)" :confirm="'¿Eliminar '.$product->name.'?'" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.app>
