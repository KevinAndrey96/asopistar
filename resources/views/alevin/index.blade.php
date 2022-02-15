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
    <h1>Siembra de Alevines</h1>
    @endif
    @if(Auth::user()->rol == 'piscicultor') 
    <a href="{{ url('alevin/create/'.$pond_id ) }}" class="btn btn-success" > Registro de siembra de alevines</a>
    @endif
    <br/>
    <br/>
    <table id= "my_table" class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>Piscicultor</th>
                <th>Ingreso</th>
                <th>Edad</th>
                <th>Estanque</th>
                <th># de Peces</th>
                <th>Especie</th>
                <th>Origen</th>
                @if(Auth::user()->rol == 'piscicultor') 
                <th>Acciones</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach( $alevins as $alevin )
            <tr>
                <!--<td>{{ $alevin->id }}</td>-->
                <td>
                    {{ $alevin->user->name }}
                </td>
                <td>{{ $alevin->date_of_entry }}</td>
                @php
                $fechaAntigua  = $alevin->date_of_entry;
                $fechaAntigua = Carbon\Carbon::createFromFormat('Y-m-d', $fechaAntigua);
                $fechaNueva  =  Carbon\Carbon::now();
                $cantidadDias = $fechaAntigua->diffInWeeks($fechaNueva);
                $age = $cantidadDias;

                $specie = $alevin->species;
                @endphp
                <td>{{ $age }} semanas</td>
                <!--<td>{{ $alevin->pond_id }}</td>-->
                <td>{{ $alevin->pond->id }}</td>
                <td>{{ $alevin->amount }}</td>
                <td>{{ $alevin->species }}</td>
                <td>{{ $alevin->source }}</td>

                @if(Auth::user()->rol == 'piscicultor') 
                <td>
                
                <a href="{{ url('/alevin/'.$alevin->id.'/edit') }}" class="btn btn-warning">
                    Editar
                </a>
                
                <form action="{{ url('/alevin/'.$alevin->id ) }}" class="d-inline" method="post">
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
    <br>
    @if(Auth::user()->rol == 'administrador') 
    <a class="btn btn-primary" href="{{ url('/user/'.$id ) }}"> Regresar</a>
    @endif
    @if(Auth::user()->rol == 'piscicultor')
    <a class="btn btn-primary" href="{{ url('/pond') }}"> Regresar</a>
    @endif
    
</div>
@endsection
