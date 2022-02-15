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
    <h1>Pesajes</h1>
    @endif
    @if(Auth::user()->rol == 'piscicultor')     
    <a href="{{ url('weight/create/'.$pond_id) }}" class="btn btn-success" > Registrar Pesaje</a>
    @endif
    <br/>
    <br/>
    <table id= "my_table" class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>Piscicultor</th>
                <th># de Estanque</th>
                <th>Muestreo</th>
                <th>Peso Total</th>
                <th># de Peces</th>
                <th>Peso Prom</th>
                @if(Auth::user()->rol == 'piscicultor') 
                <th>Acciones</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach( $weights as $weight )
            <tr>
                <td>
                    {{ $weight->user->name }}
                </td>
                <td>
                    {{ $weight->pond->id }}
                </td>
                <td>{{ $weight->agent }}</td>
                <td>{{ $weight->total_weight }} kg</td>
                <td>{{ $weight->fish_number }}</td>
                <td>{{ $weight->average_weight }} gr</td>
                
                @if(Auth::user()->rol == 'piscicultor') 
                <td>
                
                <a href="{{ url('/weight/'.$weight->id.'/edit') }}" class="btn btn-warning">
                    Editar
                </a>
                
                    
                <form action="{{ url('/weight/'.$weight->id ) }}" class="d-inline" method="post">
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
