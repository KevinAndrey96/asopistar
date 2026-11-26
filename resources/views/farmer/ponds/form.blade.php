@php $editing = $pond->exists; @endphp
<x-layouts.app :title="$editing ? 'Editar estanque '.$pond->code : 'Registrar estanque'">
    <x-page-header :title="$editing ? 'Editar estanque '.$pond->code : 'Registrar estanque'"
        :back="$editing ? [route('ponds.show', $pond), 'Estanque '.$pond->code] : [route('ponds.index'), 'Estanques']" />

    <form method="post" action="{{ $editing ? route('ponds.update', $pond) : route('ponds.store') }}" class="card max-w-2xl space-y-4 p-6">
        @csrf
        @if ($editing) @method('put') @endif
        <x-field name="code" label="Código del estanque" :value="$pond->code" maxlength="30" required hint="Como lo identificas en el predio, por ejemplo E-01." />
        <div class="grid gap-4 sm:grid-cols-2">
            <x-field name="area_m2" label="Espejo de agua" :value="$pond->area_m2" inputmode="decimal" suffix="m²" required />
            <x-field name="water_flow_lps" label="Caudal de agua" :value="$pond->water_flow_lps" inputmode="decimal" suffix="L/s" required />
        </div>
        <x-field name="equipment" label="Equipos" :value="$pond->equipment" maxlength="255" hint="Aireadores, bombas, mallas, comederos." />
        @if ($editing)
            <input type="hidden" name="active" value="0">
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="active" value="1" @checked(old('active', $pond->active)) class="rounded border-slate-300 text-water-600 focus:ring-water-500">
                Estanque activo
            </label>
        @endif
        <div class="flex gap-2">
            <button class="btn-primary">{{ $editing ? 'Guardar cambios' : 'Registrar estanque' }}</button>
            <a href="{{ $editing ? route('ponds.show', $pond) : route('ponds.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</x-layouts.app>
