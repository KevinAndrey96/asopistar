<h1>{{ $modo }} Pesajes </h1>

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
    <label for="agent"> Tipo de Muestreo </label>
    <select id="agent" name="agent" class="form-select" aria-label="Default select example">
        <option value="Control de Pesaje"  {{ old('agent') == "Control_de_Pesaje" ? 'selected' : '' }}>
        Control de Pesaje
        </option>
        <option value="Control de Crecimiento"  {{ old('agent') == 'Control_de_Crecimiento' ? 'selected' : '' }}>
        Control de Crecimiento
        </option>
        <option value="Ajuste de Dieta"  {{ old('agent') == 'Ajuste_de_Dieta' ? 'selected' : '' }}>
        Ajuste de Dieta
        </option>
    </select>
</div><!--//col-6-->

<div class="form-group">
    <label for="total_weight"> Peso Total Kg</label>
    <input type="number" class="form-control" name="total_weight" value="{{ isset($weight->total_weight)?$weight->total_weight:old('total_weight') }}" id="total_weight" step="0.01" min = "1.0" placeholder="Peso Total">
</div>

<div class="form-group">
    <label for="fish_number"> Numero de peces</label>
    <input type="number" class="form-control" name="fish_number" value="{{ isset($weight->fish_number)?$weight->fish_number:old('fish_number') }}" id="fish_number" min = "1" placeholder="# de peces">
</div>

<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">

<a class="btn btn-primary" href="{{ url('/weight/'.$pond_id) }}"> Regresar</a>
