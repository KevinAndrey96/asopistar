<h1>{{ $modo }} Estanques </h1>

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
    <label for="pond_area"> Área del estanque m<sup>2</sup></label>
    <input type="number" class="form-control" name="pond_area" value="{{ isset($pond->pond_area)?$pond->pond_area:old('pond_area') }}" id="pond_area" step="0.01" min = "1.0" placeholder="Área">
</div>

<div class="form-group">
    <label for="water"> Aforo de agua Lt/min</label>
    <input type="number" class="form-control" name="water" value="{{ isset($pond->water)?$pond->water:old('water') }}" id="water" step="0.01" min = "1.0" placeholder="Aforo">
</div>

@if($modo=="Crear")
<div>
    <label for="tools"> Equipos </label>
    <select id="tools" name="tools" class="form-select" aria-label="Default select example">
        <option value="Ninguno"  {{ old('tools') == 'Ninguno' ? 'selected' : '' }}>
        Ninguno
        </option>
        <option value="Aireadores" {{ old('tools') == 'Aireadores' ? 'selected' : '' }}>
        Aireadores
        </option>
    </select>
</div><!--//col-6-->
@else
    @if($pond->tools == "Ninguno")
    <div>
        <label for="tools"> Equipos </label>
        <select id="tools" name="tools" class="form-select" aria-label="Default select example">
            <option value="Ninguno"  {{ old('tools') == 'Ninguno' ? 'selected' : '' }}>
            Ninguno
            </option>
            <option value="Aireadores" {{ old('tools') == 'Aireadores' ? 'selected' : '' }}>
            Aireadores
            </option>
        </select>
    </div><!--//col-6-->
    @else
    <div>
        <label for="tools"> Equipos </label>
        <select id="tools" name="tools" class="form-select" aria-label="Default select example">
            <option value="Aireadores" {{ old('tools') == 'Aireadores' ? 'selected' : '' }}>
            Aireadores
            </option>
            <option value="Ninguno"  {{ old('tools') == 'Ninguno' ? 'selected' : '' }}>
            Ninguno
            </option>
        </select>
    </div><!--//col-6-->
    @endif
@endif

<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">

<a class="btn btn-primary" href="{{ url('/pond') }}"> Regresar</a>
