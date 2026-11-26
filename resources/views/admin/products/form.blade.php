@php $editing = $product->exists; @endphp
<x-layouts.app :title="$editing ? 'Editar '.$product->name : 'Nuevo producto'">
    <x-page-header :title="$editing ? 'Editar '.$product->name : 'Nuevo producto'" :back="[route('admin.products.index'), 'Productos']" />
    <form method="post" enctype="multipart/form-data" action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}" class="card max-w-3xl space-y-4 p-6">
        @csrf
        @if ($editing) @method('put') @endif
        <x-field name="name" label="Nombre" :value="$product->name" maxlength="120" required />
        <x-field name="description" label="Descripción" type="textarea" rows="3" :value="$product->description" maxlength="500" required />
        <div class="grid gap-4 sm:grid-cols-2">
            <x-field name="price_cop" label="Precio" :value="$product->price_cop" inputmode="numeric" suffix="COP" required />
            <x-field name="unit" label="Unidad" :value="$product->unit ?? 'kg'" maxlength="30" required hint="kg, unidad, bandeja de 500 g…" />
        </div>
        <input type="hidden" name="available" value="0">
        <label class="flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="available" value="1" @checked(old('available', $product->available)) class="rounded border-slate-300 text-water-600 focus:ring-water-500">
            Mostrar en el sitio público
        </label>
        @include('admin.partials.image-field', ['path' => $product->image_path])
        <div class="flex gap-2">
            <button class="btn-primary">{{ $editing ? 'Guardar cambios' : 'Crear producto' }}</button>
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</x-layouts.app>
