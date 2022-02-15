@extends('layouts.dashboard')

@section('content')
<div class="container">
    
    @if(Session::has('mensaje'))
        <div class="alert alert-warning alert-dismissible" role="alert">
            {{ Session::get('mensaje') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" ></button>
        </div>
    @endif
        
    <a href="{{ url('foodbrand/create') }}" class="btn btn-success" > Registro de Marca de Alimentos</a>
    <br/>
    <br/>
    <table id= "my_table" class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>Marca</th>
                <th>Cantidad/Proteina</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach( $foodbrands as $foodbrand )
            <tr>
                <td>{{ $foodbrand->name }}</td>
                <td>{{ $foodbrand->protein }} %</td>
                <td>
                
                <a href="{{ url('/foodbrand/'.$foodbrand->id.'/edit') }}" class="btn btn-warning">
                    Editar
                </a>
                
                    
                <form action="{{ url('/foodbrand/'.$foodbrand->id ) }}" class="d-inline" method="post">
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
