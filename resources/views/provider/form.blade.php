<h1>{{ $modo }} registro de Proveedor </h1>

@if(count($errors)>0)

    <div class="alert alert-danger" role="alert">
        <ul>
        @foreach( $errors->all() as $error )
            <li> {{ $error }} </li>
        @endforeach
        </ul>
    </div>    

@endif

<div class="form-group">
    <label for="name"> Nombre Empresa </label>
    <input type="text" class="form-control" name="name" value="{{ isset($provider->name)?$provider->name:old('name') }}" id="name" placeholder="Empresa">
</div>

<div class="form-group">
    <label for="nit"> Nit </label>
    <input type="number" class="form-control" name="nit" value="{{ isset($provider->nit)?$provider->nit:old('nit') }}" id="nit" min="1" placeholder="1">
</div>

<div class="form-group">
    <label for="city"> Ciudad </label>
    <input type="text" class="form-control" name="city" value="{{ isset($provider->city)?$provider->city:old('city') }}" id="city" placeholder="Ciudad">
</div>

<div class="form-group">
    <label for="phone"> Telefono</label>
    <input type="number" class="form-control" name="phone" value="{{ isset($provider->phone)?$provider->phone:old('phone') }}" id="phone" min="1" placeholder="3000000000">
</div>

<div class="form-group">
    <label for="representative"> Representante </label>
    <input type="text" class="form-control" name="representative" value="{{ isset($provider->representative)?$provider->representative:old('representative') }}" id="representative" placeholder="Representante">
</div>

<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">

<a class="btn btn-primary" href="{{ url('/provider') }}"> Regresar</a>
