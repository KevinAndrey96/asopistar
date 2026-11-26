@php $editing = $brand->exists; @endphp
<x-layouts.app :title="$editing ? 'Editar '.$brand->name : 'Registrar alimento'">
    <x-page-header :title="$editing ? 'Editar '.$brand->name : 'Registrar alimento'" :back="[route('admin.feed-brands.index'), 'Alimentos']" />
    <form method="post" action="{{ $editing ? route('admin.feed-brands.update', $brand) : route('admin.feed-brands.store') }}" class="card max-w-xl space-y-4 p-6">
        @csrf
        @if ($editing) @method('put') @endif
        <x-field name="name" label="Nombre comercial" :value="$brand->name" maxlength="120" required />
        <x-field name="protein_percent" label="Proteína cruda" :value="$brand->protein_percent" inputmode="decimal" suffix="%" class="sm:w-1/2" required hint="Queda guardada en cada alimentación, aunque luego cambie aquí." />
        <div class="flex gap-2">
            <button class="btn-primary">{{ $editing ? 'Guardar cambios' : 'Registrar alimento' }}</button>
            <a href="{{ route('admin.feed-brands.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</x-layouts.app>
