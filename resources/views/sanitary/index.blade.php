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
    <h1>Registro Sanitario</h1>
    @endif
    @if(Auth::user()->rol == 'piscicultor')     
    <a href="{{ url('sanitary/create/'.$pond_id) }}" class="btn btn-success" > Definir Registro Sanitario</a>
    @endif
    <br/>
    <br/>
    <table id= "my_table" class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>Piscicultor</th>
                <th># de Estanque</th>
                <th>Agente</th>
                <th>Descripcion</th>
                @if(Auth::user()->rol == 'piscicultor') 
                <th>Acciones</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach( $sanitaries as $sanitary )
            <tr>
                <td>
                    {{ $sanitary->user->name }}
                </td>
                <td>
                    {{ $sanitary->pond->id }}
                </td>
                <td>{{ $sanitary->agent }}</td>
                <td>{{ $sanitary->description }}</td>
                
                @if(Auth::user()->rol == 'piscicultor') 
                <td>
                <a href="{{ url('/sanitary/'.$sanitary->id.'/edit') }}" class="btn btn-warning">
                    Editar
                </a>
                
                    
                <form action="{{ url('/sanitary/'.$sanitary->id ) }}" class="d-inline" method="post">
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
