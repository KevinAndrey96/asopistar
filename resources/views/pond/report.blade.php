<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title> {{$user->title}} </title>

</head>
<p style="line-height: 100%; margin-top: 0.17in; margin-bottom: 0.17in">
<font size="4" style="font-size: 14pt"><b> {{$user->title}} </b></font></p>

<body lang="es-ES" link="#000080" vlink="#800000" dir="ltr"><p style="line-height: 100%; margin-top: 0.17in; margin-bottom: 0.17in">
<font size="3" style="font-size: 12pt"><b>Usuario:</b></font><font size="3" style="font-size: 12pt">
                         	{{$user->name.' '.$user->lastname}}</font></p>
<p style="line-height: 100%; margin-top: 0.17in; margin-bottom: 0.17in">
<font size="3" style="font-size: 12pt"><b>Cédula:</b></font><font size="3" style="font-size: 12pt">
    	{{$user->code}}</font></p>
<p style="line-height: 100%; margin-top: 0.17in; margin-bottom: 0.17in">
<font size="3" style="font-size: 12pt"><b>Genero:</b></font><font size="3" style="font-size: 12pt">
    	{{$user->gender}}</font></p>
<p style="line-height: 100%; margin-top: 0.17in; margin-bottom: 0.17in">
<font size="3" style="font-size: 12pt"><b>Fecha del reporte:</b></font><font size="3" style="font-size: 12pt">
    	{{$user->date}}</font></p>
<p style="line-height: 100%; margin-top: 0.17in; margin-bottom: 0.17in">

</p>
<p style="line-height: 100%; margin-top: 0.17in; margin-bottom: 0.17in">
<font size="4" style="font-size: 14pt"><b>Estanque</b></font></p>

<table class="table table-light" border="1">
	<thead class="thead-light">
		<tr>
			<th># de Estanque</th>
			<th>Área de Estanque</th>
			<th>Aforo de agua</th>
			<th>Equipos</th>
			<th>Edad</th>
			<th>Etapa</th>

		</tr>
	</thead>

	<tbody>
		@foreach( $ponds as $pond )
		<tr>
			<td>{{ $pond->pondcode }}</td>
			<td>{{ $pond->pond_area }} Km2</td>
			<td>{{ $pond->water }} lt/min</td>
			<td>{{ $pond->tools }}</td>
			<td>{{ $pond->age }} semanas</td>
			<td>{{ $pond->stage }}</td>
		</tr>
		@endforeach

	</tbody>
</table>

@if($alevin->exist == '1')
<p style="line-height: 100%; margin-top: 0.17in; margin-bottom: 0.17in">
<font size="4" style="font-size: 14pt"><b>Alevines</b></font></p>

<table class="table table-light" border="1">
	<thead class="thead-light">
		<tr>
			<th>Ingreso</th>
			<th># de Peces</th>
			<th>Especie</th>
			<th>Origen</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{ $alevin->date_of_entry }}</td>
			<td>{{ $alevin->amount }}</td>
			<td>{{ $alevin->species }}</td>
			<td>{{ $alevin->source }}</td>
		</tr>

	</tbody>
</table>

<p style="line-height: 100%; margin-top: 0.17in; margin-bottom: 0.17in">
<font size="4" style="font-size: 14pt"><b>Alimentacion</b></font></p>

<table class="table table-light" border="1">
	<thead class="thead-light">
		<tr>
			<th>Fecha Registro</th>
			<th>Cantidad de alimento</th>
			<th>Etapa</th>
			<th>Edad</th>
			<th>Peso Promedio</th>
			<th>Marca</th>
			<th>Proteina</th>
		</tr>
	</thead>

	<tbody>
		@foreach( $feedings as $feeding )
		<tr>
			<td>{{ $feeding->date_of_entry }}</td>
			<td>{{ $feeding->amount }}  Kg</td>
			<td>{{ $feeding->stage }}</td>
			<td>{{ $feeding->age }} semanas</td>
			<td>{{ $feeding->average_weight }}  gr</td>
			<td>{{ $feeding->mark }}</td>
			<td>{{ $feeding->protein }} %</td>
		</tr>
		@endforeach
	</tbody>
</table>

<p style="line-height: 100%; margin-top: 0.17in; margin-bottom: 0.17in">
<font size="4" style="font-size: 14pt"><b>Hielo</b></font></p>

<table class="table table-light" border="1">
	<thead class="thead-light">
		<tr>
			<th>Fecha Registro</th>
			<th>Pesca</th>
			<th>Cantidad sacrificio</th>
			<th>Cantidad enfriado</th>
			<th>Cantidad transporte</th>
		</tr>
	</thead>

	<tbody>
		@foreach( $ices as $ice )
		<tr>
			<td>{{ $ice->date_of_entry }}</td>
			<td>{{ $ice->fishing_amount }} Ton</td>
			<td>{{ $ice->sacrifice_amount }} kg</td>
			<td>{{ $ice->cooled_amount }} kg</td>
			<td>{{ $ice->transport_amount }} kg</td>
		</tr>
		@endforeach

	</tbody>
</table>

<p style="line-height: 100%; margin-top: 0.17in; margin-bottom: 0.17in">
<font size="4" style="font-size: 14pt"><b>Cosecha</b></font></p>

<table class="table table-light" border="1">
	<thead class="thead-light">
		<tr>
			<th>Fecha</th>
			<th>Cantidad</th>
			<th># de peces</th>
			<th>Peso prom</th>
			<th>Destino</th>
		</tr>
	</thead>

	<tbody>
		@foreach( $harvests as $harvest )
		<tr>
			<td>{{ $harvest->date_of_entry }}</td>
			<td>{{ $harvest->amount }} Ton</td>
			<td>{{ $harvest->fish_number }}</td>
			<td>{{ $harvest->average_weight }} gr</td>
			<td>{{ $harvest->destination }}</td>
		</tr>
		@endforeach

	</tbody>
</table>

<p style="line-height: 100%; margin-top: 0.17in; margin-bottom: 0.17in">
<font size="4" style="font-size: 14pt"><b>Pesos</b></font></p>

<table class="table table-light" border="1">
	<thead class="thead-light">
		<tr>
			<th>Muestreo</th>
			<th>Peso Total</th>
			<th># de Peces</th>
			<th>Peso Prom</th>
		</tr>
	</thead>

	<tbody>
		@foreach( $weights as $weight )
		<tr>
			<td>{{ $weight->agent }}</td>
			<td>{{ $weight->total_weight }} kg</td>
			<td>{{ $weight->fish_number }}</td>
			<td>{{ $weight->average_weight }} gr</td>
		</tr>
		@endforeach

	</tbody>
</table>

<p style="line-height: 100%; margin-top: 0.17in; margin-bottom: 0.17in">
<font size="4" style="font-size: 14pt"><b>Registros Sanitarios</b></font></p>

<table class="table table-light" border="1">
	<thead class="thead-light">
		<tr>
			<th>Agente</th>
			<th>Descripcion</th>
		</tr>
	</thead>

	<tbody>
		@foreach( $sanitaries as $sanitary )
		<tr>
			<td>{{ $sanitary->agent }}</td>
			<td>{{ $sanitary->description }}</td>
		</tr>
		@endforeach

	</tbody>
</table>
@endif

</body>
</html>