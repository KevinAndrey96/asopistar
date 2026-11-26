@php $editing = $provider->exists; @endphp
<x-layouts.app :title="$editing ? 'Editar '.$provider->name : 'Registrar proveedor'">
    <x-page-header :title="$editing ? 'Editar '.$provider->name : 'Registrar proveedor'" :back="[route('admin.providers.index'), 'Proveedores']" />
    <form method="post" action="{{ $editing ? route('admin.providers.update', $provider) : route('admin.providers.store') }}" class="card max-w-2xl space-y-4 p-6">
        @csrf
        @if ($editing) @method('put') @endif
        <div class="grid gap-4 sm:grid-cols-2">
            <x-field name="name" label="Razón social" :value="$provider->name" maxlength="120" required />
            <x-field name="nit" label="NIT" :value="$provider->exists ? $provider->formattedNit() : null" required hint="Con o sin dígito de verificación; se valida con el algoritmo de la DIAN." />
            <x-field name="city" label="Ciudad" :value="$provider->city" maxlength="80" required />
            <x-field name="phone" label="Teléfono" :value="$provider->phone" type="tel" />
            <x-field name="contact_name" label="Persona de contacto" :value="$provider->contact_name" maxlength="120" class="sm:col-span-2" />
        </div>
        @error('normalized_nit')<p class="text-sm text-red-700">{{ $message }}</p>@enderror
        <div class="flex gap-2">
            <button class="btn-primary">{{ $editing ? 'Guardar cambios' : 'Registrar proveedor' }}</button>
            <a href="{{ route('admin.providers.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</x-layouts.app>
