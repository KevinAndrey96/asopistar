@php
    $canLog = auth()->user()->can('log', $cycle);
    $pond = $cycle->pond;
    $weeksLeft = $profile->weeksToHarvest($metrics->week());
@endphp
<x-layouts.app :title="'Ciclo '.$cycle->species->name.' · Estanque '.$pond->code">
    <x-page-header :title="$cycle->species->name.' en el estanque '.$pond->code"
        :subtitle="'Siembra del '.$fmt->date($cycle->stocked_on).' · '.$fmt->number($cycle->stocked_fish).' alevinos de '.$fmt->grams($cycle->initial_weight_grams).($cycle->provider ? ' · '.$cycle->provider->name : '')"
        :back="[route('ponds.show', $pond), 'Estanque '.$pond->code]">
        <x-slot:actions>
            <a href="{{ route('cycles.report', $cycle) }}" class="btn-secondary">Reporte PDF</a>
        </x-slot:actions>
    </x-page-header>

    <div class="mb-6 flex flex-wrap items-center gap-3 text-sm">
        @if ($cycle->isOpen())
            <x-stage :stage="$profile->stageAt($metrics->week())" />
            <span>Semana {{ $metrics->week() }}</span>
            <span class="text-slate-500">·</span>
            <span>{{ $weeksLeft === 0 ? 'En talla de cosecha' : 'Cosecha estimada en '.$weeksLeft.' semanas' }}</span>
        @else
            <span class="rounded-full bg-slate-200 px-2 py-0.5 text-xs font-semibold text-slate-700">Cerrado</span>
            <span>El {{ $fmt->date($cycle->closed_on) }}, a las {{ $metrics->week() }} semanas</span>
        @endif
    </div>

    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">
        <x-stat label="Peces en agua" :value="$fmt->number($metrics->population())" :hint="'Sobrevivencia '.$fmt->percent($metrics->survivalRate())" />
        <x-stat label="Peso promedio" :value="$fmt->grams($metrics->averageWeightGrams())" :hint="$cycle->latestSampling ? 'Biometría del '.$fmt->date($cycle->latestSampling->sampled_on) : 'Peso de siembra'" />
        <x-stat label="Biomasa" :value="$fmt->kg($metrics->biomassKg())" />
        <x-stat label="Alimento" :value="$fmt->kg($metrics->feedKg)" />
        <x-stat label="Conversión (FCR)" :value="$fmt->ratio($metrics->feedConversionRatio())" hint="kg de alimento por kg ganado" />
        <x-stat label="Cosechado" :value="$fmt->kg($metrics->harvestedKg)" :hint="$fmt->number($metrics->harvestedFish).' peces'" />
    </div>

    <section id="alimentacion" class="mt-10 scroll-mt-6">
        <h2 class="mb-3 text-lg font-semibold">Alimentación</h2>
        @if ($canLog)
            <form method="post" action="{{ route('feedings.store', $cycle) }}" class="card mb-4 grid gap-4 p-4 sm:grid-cols-4 sm:items-end">
                @csrf
                <x-field bag="feeding" name="fed_on" label="Fecha" type="date" :value="$today" :max="$today" required />
                <x-field bag="feeding" name="feed_brand_id" label="Alimento" type="select" required>
                    <option value="">Selecciona…</option>
                    @foreach ($feedBrands as $brand)
                        <option value="{{ $brand->id }}" @selected((int) old('feed_brand_id') === $brand->id)>{{ $brand->name }} ({{ $fmt->number($brand->protein_percent, 0) }} % proteína)</option>
                    @endforeach
                </x-field>
                <x-field bag="feeding" name="kilograms" label="Cantidad" inputmode="decimal" suffix="kg" required />
                <button class="btn-primary">Registrar</button>
            </form>
        @endif
        @include('farmer.cycles.partials.table', [
            'rows' => $feedings,
            'empty' => 'Sin alimentaciones registradas.',
            'headers' => ['Fecha', 'Alimento', 'Proteína', 'Cantidad'],
            'cells' => fn ($row) => [$fmt->date($row->fed_on), $row->feedBrand->name, $fmt->number($row->protein_percent, 0).' %', $fmt->kg($row->kilograms)],
            'destroy' => fn ($row) => route('feedings.destroy', [$cycle, $row]),
        ])
    </section>

    <section id="biometria" class="mt-10 scroll-mt-6">
        <h2 class="mb-1 text-lg font-semibold">Biometrías y mortalidad</h2>
        <p class="mb-3 text-sm text-slate-600">Pesa una muestra de peces juntos; la mortalidad es la contada desde el muestreo anterior.</p>
        @if ($canLog)
            <form method="post" action="{{ route('samplings.store', $cycle) }}" class="card mb-4 grid gap-4 p-4 sm:grid-cols-5 sm:items-end">
                @csrf
                <x-field bag="sampling" name="sampled_on" label="Fecha" type="date" :value="$today" :max="$today" required />
                <x-field bag="sampling" name="sample_size" label="Peces muestreados" type="number" min="1" required />
                <div>
                    <x-field bag="sampling" name="sample_weight_grams" label="Peso de la muestra" inputmode="decimal" suffix="g" required />
                    <p id="sample-average" class="mt-1 text-xs text-water-700" aria-live="polite"></p>
                </div>
                <x-field bag="sampling" name="mortality" label="Mortalidad" type="number" min="0" value="0" />
                <button class="btn-primary">Registrar</button>
            </form>
        @endif
        @include('farmer.cycles.partials.table', [
            'rows' => $samplings,
            'empty' => 'Sin biometrías registradas.',
            'headers' => ['Fecha', 'Semana', 'Muestra', 'Peso promedio', 'Mortalidad'],
            'cells' => fn ($row) => [$fmt->date($row->sampled_on), $cycle->weekOn($row->sampled_on), $fmt->number($row->sample_size).' peces', $fmt->grams($row->averageWeightGrams()), $fmt->number($row->mortality)],
            'destroy' => fn ($row) => route('samplings.destroy', [$cycle, $row]),
        ])
    </section>

    <section id="sanidad" class="mt-10 scroll-mt-6">
        <h2 class="mb-3 text-lg font-semibold">Sanidad</h2>
        @if ($canLog)
            <form method="post" action="{{ route('treatments.store', $cycle) }}" class="card mb-4 grid gap-4 p-4 sm:grid-cols-4 sm:items-end">
                @csrf
                <x-field bag="treatment" name="applied_on" label="Fecha" type="date" :value="$today" :max="$today" required />
                <x-field bag="treatment" name="agent" label="Producto aplicado" maxlength="120" required placeholder="Cal agrícola, sal…" />
                <x-field bag="treatment" name="reason" label="Motivo" maxlength="500" required />
                <button class="btn-primary">Registrar</button>
            </form>
        @endif
        @include('farmer.cycles.partials.table', [
            'rows' => $treatments,
            'empty' => 'Sin tratamientos registrados.',
            'headers' => ['Fecha', 'Producto', 'Motivo'],
            'cells' => fn ($row) => [$fmt->date($row->applied_on), $row->agent, $row->reason],
            'destroy' => fn ($row) => route('treatments.destroy', [$cycle, $row]),
        ])
    </section>

    <section id="cosecha" class="mt-10 scroll-mt-6">
        <h2 class="mb-1 text-lg font-semibold">Cosecha y cadena de frío</h2>
        <p class="mb-3 text-sm text-slate-600">Registra el hielo usado en el sacrificio por choque térmico, el enfriamiento y el transporte.</p>
        @if ($canLog)
            <form method="post" action="{{ route('harvests.store', $cycle) }}" class="card mb-4 space-y-4 p-4">
                @csrf
                <div class="grid gap-4 sm:grid-cols-4">
                    <x-field bag="harvest" name="harvested_on" label="Fecha" type="date" :value="$today" :max="$today" required />
                    <x-field bag="harvest" name="fish_count" label="Peces cosechados" type="number" min="1" :max="$metrics->population()" required />
                    <x-field bag="harvest" name="kilograms" label="Peso total" inputmode="decimal" suffix="kg" required />
                    <x-field bag="harvest" name="destination" label="Destino" maxlength="120" required placeholder="Plaza, restaurante…" />
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <x-field bag="harvest" name="ice_slaughter_kg" label="Hielo de sacrificio" inputmode="decimal" suffix="kg" />
                    <x-field bag="harvest" name="ice_cooling_kg" label="Hielo de enfriamiento" inputmode="decimal" suffix="kg" />
                    <x-field bag="harvest" name="ice_transport_kg" label="Hielo de transporte" inputmode="decimal" suffix="kg" />
                </div>
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" name="close_cycle" value="1" class="rounded border-slate-300 text-water-600 focus:ring-water-500">
                        Es la cosecha final: cerrar el ciclo
                    </label>
                    <button class="btn-primary">Registrar cosecha</button>
                </div>
            </form>
        @endif
        @include('farmer.cycles.partials.table', [
            'rows' => $harvests,
            'empty' => 'Sin cosechas registradas.',
            'headers' => ['Fecha', 'Peces', 'Peso', 'Peso promedio', 'Hielo', 'Destino'],
            'cells' => fn ($row) => [$fmt->date($row->harvested_on), $fmt->number($row->fish_count), $fmt->kg($row->kilograms), $fmt->grams((float) $row->kilograms * 1000 / $row->fish_count), $fmt->kg($row->iceKg()), $row->destination],
            'destroy' => fn ($row) => route('harvests.destroy', [$cycle, $row]),
        ])
    </section>

    @if ($canLog)
        <section class="mt-10 rounded-xl border border-red-200 bg-red-50/40 p-4">
            <h2 class="font-semibold text-red-900">Cerrar sin cosecha final</h2>
            <p class="mt-1 text-sm text-red-900/80">Úsalo si el lote se perdió o se trasladó. Después no podrás agregar registros.</p>
            <form method="post" action="{{ route('cycles.close', $cycle) }}" class="mt-3 flex flex-wrap items-end gap-3" data-confirm="¿Cerrar el ciclo? No podrás agregar más registros.">
                @csrf
                <x-field bag="close" name="closed_on" label="Fecha de cierre" type="date" :value="$today" :max="$today" required />
                <button class="btn-danger">Cerrar ciclo</button>
            </form>
        </section>
    @endif
</x-layouts.app>
