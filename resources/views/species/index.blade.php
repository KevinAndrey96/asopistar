@extends('layouts.dashboard')

@section('content')
<div class="container">
    
    @if(Session::has('mensaje'))
        <div class="alert alert-warning alert-dismissible" role="alert">
            {{ Session::get('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" ></button>
        </div>
    @endif
        
    <a href="{{ url('species/create') }}" class="btn btn-success" > Registro de Especies</a>
    <br/>
    <br/>
    <table id= "my_table" class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>Especie</th>
                <th>Etapa Cria Final</th>
                <th>Etapa Levante Inicio</th>
                <th>Etapa Levante Final</th>
                <th>Etapa Cebo Inicio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach( $species as $specie )
            <tr>
                <td>{{ $specie->species }}</td>
                <td>{{ $specie->young_end }} semanas</td>
                <td>{{ $specie->levante_start }} semanas</td>
                <td>{{ $specie->levante_end }} semanas</td>
                <td>{{ $specie->bait_start }} semanas</td>
                <td>
                
                <a href="{{ url('/species/'.$specie->id.'/edit') }}" class="btn btn-warning">
                    Editar
                </a>
                
                    
                <form action="{{ url('/species/'.$specie->id ) }}" class="d-inline" method="post">
                    @csrf
                    {{ method_field('DELETE') }}
                    <input class="btn btn-danger" type="submit" onclick="return confirm('¿Quieres borrar?')" 
                    value="Borrar">

                </form>

                </td>
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
</div>
@endsection
