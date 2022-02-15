<h1>{{ $modo }} Marca de Alimentos </h1>

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
    <label for="name"> Marca </label>
    <input type="text" class="form-control" name="name" value="{{ isset($foodbrand->name)?$foodbrand->name:old('name') }}" id="name" placeholder="Marca">
</div>

<div class="form-group">
    <label for="protein"> Proteína %</label>
    <input type="number" class="form-control" name="protein" value="{{ isset($foodbrand->protein)?$foodbrand->protein:old('protein') }}" id="protein" step="0.01" min = "1.0" placeholder="1.0">
</div>

<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">

<a class="btn btn-primary" href="{{ url('/foodbrand') }}"> Regresar</a>
