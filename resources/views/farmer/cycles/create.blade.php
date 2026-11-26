<x-layouts.app :title="'Siembra en el estanque '.$pond->code">
    <x-page-header :title="'Siembra en el estanque '.$pond->code" subtitle="Registrar los alevinos abre un ciclo productivo nuevo."
        :back="[route('ponds.show', $pond), 'Estanque '.$pond->code]" />

    @if ($species->isEmpty())
        <x-empty>La asociación aún no ha registrado especies. Pide al administrador que las cree.</x-empty>
    @else
        <form method="post" action="{{ route('cycles.store', $pond) }}" class="card max-w-2xl space-y-4 p-6">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <x-field name="species_id" label="Especie" type="select" required>
                    <option value="">Selecciona…</option>
                    @foreach ($species as $item)
                        <option value="{{ $item->id }}" @selected((int) old('species_id') === $item->id)>{{ $item->name }}</option>
                    @endforeach
                </x-field>
                <x-field name="provider_id" label="Proveedor de alevinos" type="select">
                    <option value="">Sin proveedor registrado</option>
                    @foreach ($providers as $provider)
                        <option value="{{ $provider->id }}" @selected((int) old('provider_id') === $provider->id)>{{ $provider->name }}</option>
                    @endforeach
                </x-field>
            </div>
            <div class="grid gap-4 sm:grid-cols-3">
                <x-field name="stocked_fish" label="Alevinos sembrados" type="number" min="1" step="1" required />
                <x-field name="initial_weight_grams" label="Peso promedio" inputmode="decimal" suffix="g" required hint="Peso de un alevino al sembrar." />
                <x-field name="stocked_on" label="Fecha de siembra" type="date" :value="now()->toDateString()" :max="now()->toDateString()" required />
            </div>
            <div class="flex gap-2">
                <button class="btn-primary">Registrar siembra</button>
                <a href="{{ route('ponds.show', $pond) }}" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    @endif
</x-layouts.app>
