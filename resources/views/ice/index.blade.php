@extends('layouts.dashboard')

@section('content')
<div class="container">
    
    @if(Session::has('mensaje'))
        <div class="alert alert-warning alert-dismissible" role="alert">
            {{ Session::get('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" ></button>
        </div>
    @endif

    @if(Auth::user()->rol == 'administrador') 
    <h1>Suministro de hielo</h1>
    @endif
    @if(Auth::user()->rol == 'piscicultor')     
    <a href="{{ url('ice/create/'.$pond_id) }}" class="btn btn-success" > Registro de suministro de hielo</a>
    @endif
    <br/>
    <br/>
    <table id= "my_table" class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>Piscicultor</th>
                <th>Fecha</th>
                <th>Estanque</th>
                <th>Especie</th>
                <th>Pesca</th>
                <th>Cantidad sacrificio</th>
                <th>Cantidad enfriado</th>
                <th>Cantidad transporte</th>
                @if(Auth::user()->rol == 'piscicultor') 
                <th>Acciones</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach( $ices as $ice )
            <tr>
                <!--<td>{{ $ice->id }}</td>-->
                <td>
                    {{ $ice->user->name }}
                </td>
                <td>{{ $ice->date_of_entry }}</td>
                <td>
                    {{ $ice->pond->id }}
                </td>
                <td>{{ $ice->species }}</td>
                <td>{{ $ice->fishing_amount }} Ton</td>
                <td>{{ $ice->sacrifice_amount }} kg</td>
                <td>{{ $ice->cooled_amount }} kg</td>
                <td>{{ $ice->transport_amount }} kg</td>
                
                @if(Auth::user()->rol == 'piscicultor') 
                <td>
                <a href="{{ url('/ice/'.$ice->id.'/edit') }}" class="btn btn-warning">
                    Editar
                </a>
                
                <form action="{{ url('/ice/'.$ice->id ) }}" class="d-inline" method="post">
                    @csrf
                    {{ method_field('DELETE') }}
                    <input class="btn btn-danger" type="submit" onclick="return confirm('¿Quieres borrar?')" 
                    value="Borrar">

                </form>

                </td>
                @endif
            </tr>
            @endforeach

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
    @if(Auth::user()->rol == 'administrador') 
    <a class="btn btn-primary" href="{{ url('/user/'.$id ) }}"> Regresar</a>
    @endif
    @if(Auth::user()->rol == 'piscicultor')
    <a class="btn btn-primary" href="{{ url('/pond') }}"> Regresar</a>
    @endif
</div>
@endsection
