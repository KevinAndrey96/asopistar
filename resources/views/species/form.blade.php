<h1>{{ $modo }} registro de Especie </h1>

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
    <label for="species"> Especie </label>
    <input type="text" class="form-control" name="species" value="{{ isset($specie->species)?$specie->species:old('species') }}" id="species" placeholder="Especie">
</div>

<div class="form-group">
    <label for="young_end"> Etapa Cria Final semanas</label>
    <input type="number" class="form-control" name="young_end" value="{{ isset($specie->young_end)?$specie->young_end:old('young_end') }}" id="young_end" min="1" placeholder="0">
</div>

<div class="form-group">
    <label for="levante_start"> Etapa Levante Inicio semanas</label>
    <input type="number" class="form-control" name="levante_start" value="{{ isset($specie->levante_start)?$specie->levante_start:old('levante_start') }}" id="levante_start" min="1" placeholder="0">
</div>

<div class="form-group">
    <label for="levante_end"> Etapa Levante Final semanas</label>
    <input type="number" class="form-control" name="levante_end" value="{{ isset($specie->levante_end)?$specie->levante_end:old('levante_end') }}" id="levante_end" min="1" placeholder="0">
</div>

<div class="form-group">
    <label for="bait_start"> Etapa Cebo Inicial semanas</label>
    <input type="number" class="form-control" name="bait_start" value="{{ isset($specie->bait_start)?$specie->bait_start:old('bait_start') }}" id="bait_start" min="1" placeholder="0">
</div>

<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">

<a class="btn btn-primary" href="{{ url('/species') }}"> Regresar</a>
