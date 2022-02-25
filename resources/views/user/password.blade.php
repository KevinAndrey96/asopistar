@extends('layouts.dashboard')

@section('content')
<div class="container">
    <form action="{{ url('/user/'.$user->id) }}" method="post" enctype="multipart/form-data"> 
        @csrf
        {{ method_field('PATCH') }}

        @if(count($errors)>0)
            <div class="alert alert-danger" role="alert">
                <ul>
                @foreach( $errors->all() as $error )
                    <li> {{ $error }} </li>
                @endforeach
                </ul>
            </div>    

        @endif
        <div class="password">
            <label for="password" >Contraseña actual</label>
            <input id="oldpassword" type="password" class="form-control @error('password') is-invalid @enderror" name="oldpassword" required autocomplete="new-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="password">
            <label for="password" >Nueva Contraseña</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <input type="hidden" class="form-control" name="updatepass" value="1" id="updatepass" readonly="readonly">
        </div>

        </br>
        <input class="btn btn-success" type="submit" value="Cambiar Contraseña">
        @if(Auth::user()->rol == 'administrador') 
        <a class="btn btn-primary" href="{{ url('/admin') }}"> Regresar</a>
        @endif
        @if(Auth::user()->rol == 'piscicultor')
        <a class="btn btn-primary" href="{{ url('/home') }}"> Regresar</a>
        @endif
    </form>

</div>

@endsection

