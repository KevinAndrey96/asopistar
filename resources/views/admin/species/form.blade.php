@php $editing = $species->exists; @endphp
<x-layouts.app :title="$editing ? 'Editar '.$species->name : 'Registrar especie'">
    <x-page-header :title="$editing ? 'Editar '.$species->name : 'Registrar especie'" :back="[route('admin.species.index'), 'Especies']" />
    <form method="post" action="{{ $editing ? route('admin.species.update', $species) : route('admin.species.store') }}" class="card max-w-2xl space-y-4 p-6">
        @csrf
        @if ($editing) @method('put') @endif
        <div class="grid gap-4 sm:grid-cols-2">
            <x-field name="name" label="Nombre común" :value="$species->name" maxlength="80" required />
            <x-field name="scientific_name" label="Nombre científico" :value="$species->scientific_name" maxlength="120" />
        </div>
        <div class="grid gap-4 sm:grid-cols-3">
            <x-field name="fry_until_week" label="Cría hasta la semana" type="number" min="1" :value="$species->fry_until_week" required />
            <x-field name="grow_out_until_week" label="Levante hasta la semana" type="number" min="2" :value="$species->grow_out_until_week" required />
            <x-field name="harvest_week" label="Semana de cosecha" type="number" min="3" :value="$species->harvest_week" required />
        </div>
        <x-field name="market_weight_grams" label="Peso comercial" type="number" min="1" :value="$species->market_weight_grams" suffix="g" class="sm:w-1/3" required hint="Peso por pez con el que se proyecta la cosecha." />
        <div class="flex gap-2">
            <button class="btn-primary">{{ $editing ? 'Guardar cambios' : 'Registrar especie' }}</button>
            <a href="{{ route('admin.species.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</x-layouts.app>
