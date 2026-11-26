<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte del estanque {{ $cycle->pond->code }}</title>
    @include('reports.partials.style')
</head>
<body>
    <footer>{{ config('app.name') }} · Generado el {{ $fmt->date(now()) }} · Ciclo #{{ $cycle->id }}</footer>

    <table class="header">
        <tr>
            <td>
                <h1>Estanque {{ $cycle->pond->code }} · {{ $cycle->species->name }}</h1>
                <p class="muted">{{ $cycle->pond->user->fullName() }} · Cédula {{ $cycle->pond->user->document }}{{ $cycle->pond->user->vereda ? ' · Vereda '.$cycle->pond->user->vereda : '' }}</p>
            </td>
            <td class="num muted">
                Siembra: {{ $fmt->date($cycle->stocked_on) }}<br>
                {{ $cycle->isOpen() ? 'Ciclo abierto, semana '.$metrics->week() : 'Cerrado el '.$fmt->date($cycle->closed_on) }}<br>
                Etapa: {{ $profile->stageAt($metrics->week())->label() }}
            </td>
        </tr>
    </table>

    <h2>Indicadores</h2>
    <table class="kpis">
        <tr>
            <td>Alevinos sembrados<strong>{{ $fmt->number($cycle->stocked_fish) }}</strong></td>
            <td>Peces en agua<strong>{{ $fmt->number($metrics->population()) }}</strong></td>
            <td>Sobrevivencia<strong>{{ $fmt->percent($metrics->survivalRate()) }}</strong></td>
            <td>Peso promedio<strong>{{ $fmt->grams($metrics->averageWeightGrams()) }}</strong></td>
        </tr>
        <tr>
            <td>Biomasa estimada<strong>{{ $fmt->kg($metrics->biomassKg()) }}</strong></td>
            <td>Alimento suministrado<strong>{{ $fmt->kg($metrics->feedKg) }}</strong></td>
            <td>Conversión alimenticia<strong>{{ $fmt->ratio($metrics->feedConversionRatio()) }}</strong></td>
            <td>Cosechado<strong>{{ $fmt->kg($metrics->harvestedKg) }}</strong></td>
        </tr>
    </table>

    <h2>Estanque</h2>
    <table>
        <tr><th>Espejo de agua</th><th>Caudal</th><th>Equipos</th><th>Proveedor de alevinos</th></tr>
        <tr>
            <td>{{ $fmt->number($cycle->pond->area_m2) }} m²</td>
            <td>{{ $fmt->number($cycle->pond->water_flow_lps, 1) }} L/s</td>
            <td>{{ $cycle->pond->equipment ?? 'Sin registrar' }}</td>
            <td>{{ $cycle->provider ? $cycle->provider->name.' (NIT '.$cycle->provider->formattedNit().')' : 'Sin registrar' }}</td>
        </tr>
    </table>

    <h2>Alimentación</h2>
    @if ($feedings->isEmpty())
        <p class="muted">Sin registros.</p>
    @else
        <table>
            <tr><th>Fecha</th><th>Semana</th><th>Alimento</th><th class="num">Proteína</th><th class="num">Cantidad</th></tr>
            @foreach ($feedings as $row)
                <tr><td>{{ $fmt->date($row->fed_on) }}</td><td>{{ $cycle->weekOn($row->fed_on) }}</td><td>{{ $row->feedBrand->name }}</td><td class="num">{{ $fmt->number($row->protein_percent, 0) }} %</td><td class="num">{{ $fmt->kg($row->kilograms) }}</td></tr>
            @endforeach
        </table>
    @endif

    <h2>Biometrías y mortalidad</h2>
    @if ($samplings->isEmpty())
        <p class="muted">Sin registros.</p>
    @else
        <table>
            <tr><th>Fecha</th><th>Semana</th><th class="num">Muestra</th><th class="num">Peso promedio</th><th class="num">Mortalidad</th></tr>
            @foreach ($samplings as $row)
                <tr><td>{{ $fmt->date($row->sampled_on) }}</td><td>{{ $cycle->weekOn($row->sampled_on) }}</td><td class="num">{{ $fmt->number($row->sample_size) }}</td><td class="num">{{ $fmt->grams($row->averageWeightGrams()) }}</td><td class="num">{{ $fmt->number($row->mortality) }}</td></tr>
            @endforeach
        </table>
    @endif

    <h2>Sanidad</h2>
    @if ($treatments->isEmpty())
        <p class="muted">Sin registros.</p>
    @else
        <table>
            <tr><th>Fecha</th><th>Producto</th><th>Motivo</th></tr>
            @foreach ($treatments as $row)
                <tr><td>{{ $fmt->date($row->applied_on) }}</td><td>{{ $row->agent }}</td><td>{{ $row->reason }}</td></tr>
            @endforeach
        </table>
    @endif

    <h2>Cosechas y cadena de frío</h2>
    @if ($harvests->isEmpty())
        <p class="muted">Sin registros.</p>
    @else
        <table>
            <tr><th>Fecha</th><th class="num">Peces</th><th class="num">Peso</th><th class="num">Hielo sacrificio</th><th class="num">Hielo enfriamiento</th><th class="num">Hielo transporte</th><th>Destino</th></tr>
            @foreach ($harvests as $row)
                <tr><td>{{ $fmt->date($row->harvested_on) }}</td><td class="num">{{ $fmt->number($row->fish_count) }}</td><td class="num">{{ $fmt->kg($row->kilograms) }}</td><td class="num">{{ $fmt->kg($row->ice_slaughter_kg) }}</td><td class="num">{{ $fmt->kg($row->ice_cooling_kg) }}</td><td class="num">{{ $fmt->kg($row->ice_transport_kg) }}</td><td>{{ $row->destination }}</td></tr>
            @endforeach
        </table>
    @endif
</body>
</html>
