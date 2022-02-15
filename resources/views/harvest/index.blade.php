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
    <h1>Cosecha</h1>
    @endif
    @if(Auth::user()->rol == 'piscicultor')     
    <a href="{{ url('harvest/create/'.$pond_id) }}" class="btn btn-success" > Registro de cosecha</a>
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
                <th>Cantidad</th>
                <th># de peces</th>
                <th>Peso prom</th>
                <th>Destino</th>
                @if(Auth::user()->rol == 'piscicultor') 
                <th>Acciones</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach( $harvests as $harvest )
            <tr>
                <!--<td>{{ $harvest->id }}</td>-->
                <td>
                    {{ $harvest->user->name }}
                </td>
                <td>{{ $harvest->date_of_entry }}</td>
                <td>
                    {{ $harvest->pond->id }}
                </td>
                <td>{{ $harvest->species }}</td>
                <td>{{ $harvest->amount }} Ton</td>
                <td>{{ $harvest->fish_number }}</td>
                <td>{{ $harvest->average_weight }} gr</td>
                <td>{{ $harvest->destination }}</td>
                
                @if(Auth::user()->rol == 'piscicultor') 
                <td>
                
                <a href="{{ url('/harvest/'.$harvest->id.'/edit') }}" class="btn btn-warning">
                    Editar
                </a>
                
                    
                <form action="{{ url('/harvest/'.$harvest->id ) }}" class="d-inline" method="post">
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
