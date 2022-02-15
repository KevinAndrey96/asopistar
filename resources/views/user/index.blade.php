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
    <a href="{{ url('user/create') }}" class="btn btn-success" > Registrar nuevo usuario </a>
    @endif
    <br/>
    <br/>
    <table id= "my_table" class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>Cédula</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Vereda</th>
                <th>Predio</th>
                <th>Rol</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach( $users as $user )
            <tr>
                <!--<td>{{ $user->id }}</td>-->
                <td>{{ $user->code }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->estate }}</td>
                <td>{{ $user->sidewalk }}</td>
                <td>{{ $user->rol }}</td>
                
                <td>
                    @if($user->is_enabled ==1)
                        Si
                    @else
                        No
                    @endif
                </td>
                <td>
                
                @if($user->rol != 'administrador')
                <a href="{{ url('/user/'.$user->id ) }}" class="btn btn-success">
                    Estanques
                </a>
                @endif

                @if(Auth::user()->rol == 'administrador') 
                <a href="{{ url('/user/'.$user->id.'/edit') }}" class="btn btn-warning">
                    Editar
                </a>
                
                   
                <form action="{{ url('/user/'.$user->id ) }}" class="d-inline" method="post">
                    @csrf
                    {{ method_field('DELETE') }}
                    <input class="btn btn-danger" type="submit" onclick="return confirm('¿Quieres borrar?')" 
                    value="Borrar">

                </form>
                @endif

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
