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
    <h1>Alimentación</h1>
    @endif
    @if(Auth::user()->rol == 'piscicultor') 
    <a href="{{ url('feeding/create/'.$pond_id) }}" class="btn btn-success" > Registro de alimentación</a>
    @endif
    <br/>
    <br/>
    <table id= "my_table" class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>Piscicultor</th>
                <th>Fecha</th>
                <th>Estanque</th>
                <th>Cantidad</th>
                <th>Etapa</th>
                <th>Edad</th>
                <th># Peces</th>
                <th>Peso Prom</th>
                <th>Marca</th>
                <th>Proteina</th>
                @if(Auth::user()->rol == 'piscicultor') 
                <th>Acciones</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach( $feedings as $feeding )
            <tr>
                <!--<td>{{ $feeding->id }}</td>-->
                <td>
                    {{ $feeding->user->name }}
                </td>
                <td>{{ $feeding->date_of_entry }}</td>
                <td>
                    {{ $feeding->pond->id }}
                </td>
                <td>{{ $feeding->amount }}  Kg</td>
                <td>{{ $feeding->stage }}</td>
                <td>{{ $feeding->age }} semanas</td>
                <td>{{ $feeding->number_of_fish }}</td>
                <td>{{ $feeding->average_weight }}  gr</td>
                <td>{{ $feeding->mark }}</td>
                <td>{{ $feeding->protein }} %</td>
                
                @if(Auth::user()->rol == 'piscicultor') 
                <td>
                <a href="{{ url('/feeding/'.$feeding->id.'/edit') }}" class="btn btn-warning">
                    Editar
                </a>
                    
                <form action="{{ url('/feeding/'.$feeding->id ) }}" class="d-inline" method="post">
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
