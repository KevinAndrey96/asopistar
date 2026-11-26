<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de la asociación</title>
    @include('reports.partials.style')
</head>
<body>
    <footer>{{ config('app.name') }} · Generado el {{ $fmt->date($report->generatedOn) }}</footer>

    <h1>Reporte de la asociación</h1>
    <p class="muted">Indicadores al {{ $fmt->date($report->generatedOn) }} calculados con los ciclos abiertos de los estanques activos.</p>

    <h2>Resumen</h2>
    <table class="kpis">
        <tr>
            <td>Piscicultores activos<strong>{{ $report->farmers }}</strong></td>
            <td>Estanques activos<strong>{{ $report->activePonds }}</strong></td>
            <td>Ciclos en curso<strong>{{ $report->openCycles }}</strong></td>
            <td>Peces en agua<strong>{{ $fmt->number($report->fishInWater) }}</strong></td>
        </tr>
        <tr>
            <td>Biomasa estimada<strong>{{ $fmt->kg($report->biomassKg) }}</strong></td>
            <td>Alimento en ciclos abiertos<strong>{{ $fmt->kg($report->feedKg) }}</strong></td>
            <td>Cosechado en {{ $report->generatedOn->year }}<strong>{{ $fmt->kg($report->harvestedKgThisYear) }}</strong></td>
            <td>Piscicultores por género<strong style="font-size: 10px">@foreach ($report->farmersByGender as $gender => $count){{ $gender }}: {{ $count }}@if (! $loop->last) · @endif @endforeach</strong></td>
        </tr>
    </table>

    <h2>Etapas y cosecha proyectada</h2>
    <table>
        <tr>
            @foreach ($report->cyclesByStage as $stage => $count)<th class="num">{{ $stage }}</th>@endforeach
            @foreach ($report->projectedKgWithin as $weeks => $kg)<th class="num">Cosecha en ≤ {{ $weeks }} semanas</th>@endforeach
        </tr>
        <tr>
            @foreach ($report->cyclesByStage as $count)<td class="num">{{ $count }} ciclos</td>@endforeach
            @foreach ($report->projectedKgWithin as $kg)<td class="num">{{ $fmt->kg($kg) }}</td>@endforeach
        </tr>
    </table>

    <h2>Producción por especie</h2>
    @if ($report->bySpecies === [])
        <p class="muted">No hay ciclos abiertos.</p>
    @else
        <table>
            <tr><th>Especie</th><th class="num">Ciclos</th><th class="num">Peces</th><th class="num">Biomasa</th><th class="num">Cosecha proyectada</th></tr>
            @foreach ($report->bySpecies as $row)
                <tr><td>{{ $row['species'] }}</td><td class="num">{{ $row['cycles'] }}</td><td class="num">{{ $fmt->number($row['fish']) }}</td><td class="num">{{ $fmt->kg($row['biomassKg']) }}</td><td class="num">{{ $fmt->kg($row['projectedKg']) }}</td></tr>
            @endforeach
        </table>
    @endif

    <h2>Piscicultores</h2>
    <table>
        <tr><th>Nombre</th><th>Cédula</th><th>Vereda</th><th class="num">Estanques</th><th class="num">Ciclos</th><th class="num">Peces</th><th class="num">Biomasa</th></tr>
        @foreach ($report->byFarmer as $row)
            <tr><td>{{ $row['farmer']->fullName() }}</td><td>{{ $row['farmer']->document }}</td><td>{{ $row['farmer']->vereda }}</td><td class="num">{{ $row['ponds'] }}</td><td class="num">{{ $row['cycles'] }}</td><td class="num">{{ $fmt->number($row['fish']) }}</td><td class="num">{{ $fmt->kg($row['biomassKg']) }}</td></tr>
        @endforeach
    </table>
</body>
</html>
