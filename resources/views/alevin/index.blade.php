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
    @if(Auth::user()->rol == 'piscicultor' && $alevinexist == 0) 
    <a href="{{ url('alevin/create/'.$pond_id ) }}" class="btn btn-success" > Registro de siembra de alevines</a>
    @elseif(Auth::user()->rol == 'piscicultor' && $alevinexist == 1)
    <h1>Siembra de Alevines</h1>
    @endif
    <br/>
    <br/>
    <table id= "my_table" class="table table-light">
        <thead class="thead-light">
            <tr>
                <th>Piscicultor</th>
                <th>Ingreso</th>
                @if(Auth::user()->rol == 'piscicultor') 
                <th>Edad</th>
                @endif
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
                    {{ $alevin->user->name.' '.$alevin->user->lastname }}
                </td>
                <td>{{ $alevin->date_of_entry }}</td>
                @if(Auth::user()->rol == 'piscicultor') 
                <td>{{ $alevin->age }} semanas</td>
                @endif
                <!--<td>{{ $alevin->pond_id }}</td>-->
                <td>{{ $alevin->pond->pondcode }}</td>
                <td>{{ $alevin->amount }}</td>
                <td>{{ $alevin->species }}</td>
                <td>{{ $alevin->source }}</td>

                @if(Auth::user()->rol == 'piscicultor') 
                <td>
                
                <a href="{{ url('/alevin/'.$alevin->id.'/edit') }}" class="btn btn-warning">
                	<span class="nav-icon">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
							<path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
						</svg>
					</span>
                </a>
                
                <form action="{{ url('/alevin/'.$alevin->id ) }}" class="d-inline" method="post">
                    @csrf
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-danger"onclick="return confirm('??Quieres borrar?')">
                        <span class="nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                        </span>  
                    </button>
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
    <a class="btn btn-primary" href="{{ url('/user/'.$userid ) }}"> Regresar</a>
    @endif
    @if(Auth::user()->rol == 'piscicultor')
    <a class="btn btn-primary" href="{{ url('/pond') }}"> Regresar</a>
    @endif
    
</div>
@endsection
