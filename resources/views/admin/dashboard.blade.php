<x-layouts.app title="Resumen de la asociación">
    <x-page-header title="Resumen de la asociación" :subtitle="'Indicadores al '.$fmt->date($report->generatedOn).' con los ciclos abiertos.'">
        <x-slot:actions>
            <a href="{{ route('admin.report') }}" class="btn-secondary">Reporte PDF</a>
            <a href="{{ route('admin.farmers.create') }}" class="btn-primary">Registrar piscicultor</a>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <x-stat label="Piscicultores activos" :value="$report->farmers" />
        <x-stat label="Estanques activos" :value="$report->activePonds" :hint="$report->openCycles.' con siembra en curso'" />
        <x-stat label="Peces en agua" :value="$fmt->number($report->fishInWater)" :hint="'Biomasa estimada '.$fmt->kg($report->biomassKg)" />
        <x-stat label="Cosechado este año" :value="$fmt->kg($report->harvestedKgThisYear)" :hint="'Alimento en ciclos abiertos '.$fmt->kg($report->feedKg)" />
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <section class="card p-5">
            <h2 class="font-semibold">Ciclos por etapa</h2>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach ($report->cyclesByStage as $stage => $count)
                    <li class="flex justify-between"><span>{{ $stage }}</span><span class="font-semibold tabular-nums">{{ $count }}</span></li>
                @endforeach
            </ul>
        </section>
        <section class="card p-5">
            <h2 class="font-semibold">Cosecha proyectada</h2>
            <p class="text-xs text-slate-500">Peces en agua que llegan a la semana de cosecha, al peso comercial de su especie.</p>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach ($report->projectedKgWithin as $weeks => $kg)
                    <li class="flex justify-between"><span>En {{ $weeks }} semanas o menos</span><span class="font-semibold tabular-nums">{{ $fmt->kg($kg) }}</span></li>
                @endforeach
            </ul>
        </section>
        <section class="card p-5">
            <h2 class="font-semibold">Piscicultores por género</h2>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach ($report->farmersByGender as $gender => $count)
                    <li class="flex justify-between"><span>{{ $gender }}</span><span class="font-semibold tabular-nums">{{ $count }}</span></li>
                @endforeach
            </ul>
        </section>
    </div>

    <section class="mt-6">
        <h2 class="mb-3 text-lg font-semibold">Producción por especie</h2>
        @if ($report->bySpecies === [])
            <x-empty>No hay ciclos abiertos.</x-empty>
        @else
            <div class="card overflow-x-auto">
                <table class="table-base table-stack">
                    <thead><tr><th>Especie</th><th class="num">Ciclos</th><th class="num">Peces</th><th class="num">Biomasa</th><th class="num">Cosecha proyectada</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($report->bySpecies as $row)
                            <tr>
                                <td data-label="Especie">{{ $row['species'] }}</td>
                                <td data-label="Ciclos" class="num">{{ $row['cycles'] }}</td>
                                <td data-label="Peces" class="num">{{ $fmt->number($row['fish']) }}</td>
                                <td data-label="Biomasa" class="num">{{ $fmt->kg($row['biomassKg']) }}</td>
                                <td data-label="Cosecha proyectada" class="num">{{ $fmt->kg($row['projectedKg']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-layouts.app>
