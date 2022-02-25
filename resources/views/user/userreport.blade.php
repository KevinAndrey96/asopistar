@extends('layouts.dashboard')

@section('content')

<div class="container">
    @if($isusers == 0)
        <h1> Reporte </h1>
    @else
        <h1> Reporte Global</h1>
    @endif
    
    <br/>
    <table id= "my_table" class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>Nombre</th>
                <th>Genero</th>
                <th>Estanques</th>
                <th>Comida Semana Actual</th>
                <th>Estanques cria</th>
                <th>Estanques levante</th>
                <th>Estanques cebo</th>
                <th>Estanques Cosecha</th>
                <th>Cosecha Aproximada</th>
                <th>Reportes sanitarios</th>
            </tr>
        </thead>

        <tbody>           
            @if($isusers == 0)
            <tr>
                
                <td>{{ $user->name.' '.$user->lastname }}</td>
                <td>{{ $user->gender }}</td>
                <td>
                    {{ 'Activos   '.$user->activeponds }}
                </br>
                    {{ 'Inactivos '.$user->inactiveponds }}
                </td>
                <td>{{ $user->foodcount }} kg</td>
                <td>{{ $user->youngcount }}</td>
                <td>{{ $user->levantecount }}</td>
                <td>{{ $user->baitcount }}</td>
                <td>{{ $user->harvestcount }}</td>
                <td>
                    1 Mes {{ $user->week4weight }} Kg
                    </br>
                    3 Mes {{ $user->week12weight }} Kg
                    </br>
                    6 Mes {{ $user->week24weight }} Kg
                </td>
                <td>{{ $user->sanitarycount }}</td>
                
            </tr>
            @else
                @foreach($users as $user)
                <tr>
                    
                    <td>{{ $user->name.' '.$user->lastname }}</td>
                    <td>{{ $user->gender }}</td>
                    <td>
                        {{ 'Activos   '.$user->activeponds }}
                    </br>
                        {{ 'Inactivos '.$user->inactiveponds }}
                    </td>
                    <td>{{ $user->foodcount }} kg</td>
                    <td>{{ $user->youngcount }}</td>
                    <td>{{ $user->levantecount }}</td>
                    <td>{{ $user->baitcount }}</td>
                    <td>{{ $user->harvestcount }}</td>
                    <td>
                        1 Mes {{ $user->week4weight }} Kg
                        </br>
                        3 Mes {{ $user->week12weight }} Kg
                        </br>
                        6 Mes {{ $user->week24weight }} Kg
                    </td>
                    <td>{{ $user->sanitarycount }}</td>
                    
                </tr>
                @endforeach
        </tbody>

        <tbody>
            <tr>
                <td>Total</td>
                <td>
                    {{'# F: '.$total['femalecount']}}
                    </br>
                    {{'# M: '.$total['malecount']}}
                </td>
                <td>
                    {{ 'Activos   '.$total['activeponds']}}
                    </br>
                    {{ 'Inactivos '.$total['inactiveponds']}}
                </td>
                <td>{{ $total['foodcount']}} kg</td>
                <td>{{ $total['youngcount']}}</td>
                <td>{{ $total['levantecount']}}</td>
                <td>{{ $total['baitcount']}}</td>
                <td>{{ $total['harvestcount'] }}</td>
                <td>
                    1 Mes {{ $total['week4weight'] }} Kg
                    </br>
                    3 Mes {{ $total['week12weight'] }} Kg
                    </br>
                    6 Mes {{ $total['week24weight'] }} Kg
                </td>
                <td>{{ $total['sanitarycount']}}</td>
            </tr>
            @endif  
        </tbody>
    </table>
    <script>
        $(document).ready( function () {
            $('#my_table').DataTable({
				"language": {
      				"url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
				}});
        } );
    </script>
    <br>
    @if($isusers == 0)
    <a class="btn btn-primary" href="{{ url('/user/'.$id ) }}"> Regresar</a>
    @endif
</div>
@endsection