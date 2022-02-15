<h1>{{ $modo }} Registro sanitario </h1>

@if(count($errors)>0)

    <div class="alert alert-danger" role="alert">
        <ul>
        @foreach( $errors->all() as $error )
            <li> {{ $error }} </li>
        @endforeach
        </ul>
    </div>    

@endif

@if($modo=="Crear")
<div class="form-group">
    <input type="hidden" class="form-control" name="pond_id" value="{{$pond_id}}" id="pond_id" readonly="readonly">
</div>
@endif

<div>
    <label for="agent"> Equipos </label>
    <select id="agent" name="agent" class="form-select" aria-label="Default select example">
        <option value="Hongos"  {{ old('agent') == 'Hongos' ? 'selected' : '' }}>
        Hongos
        </option>
        <option value="Bacterias"  {{ old('agent') == 'Bacterias' ? 'selected' : '' }}>
        Bacterias
        </option>
        <option value="Virus"  {{ old('agent') == 'Virus' ? 'selected' : '' }}>
        Virus
        </option>
        <option value="Parasitos"  {{ old('agent') == 'Parasitos' ? 'selected' : '' }}>
        Parasitos
        </option>
        <option value="Contaminación"  {{ old('agent') == 'Contaminación' ? 'selected' : '' }}>
        Contaminación
        </option>
    </select>
</div>

<div class="form-group">
    <label for="description"> Sintomatologia </label>
    <input type="text" class="form-control" name="description" value="{{ isset($sanitary->description)?$sanitary->description:old('description') }}" id="description" >
</div>

<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">

<a class="btn btn-primary" href="{{ url('/sanitary/'.$pond_id) }}"> Regresar</a>
