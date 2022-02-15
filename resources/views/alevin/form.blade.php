<h1>{{ $modo }} siembra de alevines </h1>

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
    <label for="species"> Especie </label>
    <select id="species" name="species" class="form-select" aria-label="Default select example">
        @foreach( $species as $specie )
            <option value="{{ $specie->species }}" > {{ $specie->species }} </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="amount"> # de Peces</label>
    <input type="number" class="form-control" name="amount" value="{{ isset($alevin->amount)?$alevin->amount:old('amount') }}" id="amount" step="0.01" min = "1.0" placeholder="# de Peces">
</div>

<div>
    <label for="source"> Origen </label>
    <select id="source" name="source" class="form-select" aria-label="Default select example">
        @foreach( $providers as $provider )
            <option value="{{ $provider->name }}" > {{ $provider->name }} </option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="date_of_entry"> Ingreso </label>
    <input type="date" class="form-control" name="date_of_entry" value="{{ isset($alevin->date_of_entry)?$alevin->date_of_entry:old('date_of_entry') }}" id="date_of_entry" placeholder="aa/mm/dd">
</div>
<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">


<a class="btn btn-primary" href="{{ url('/alevin/'.$pond_id) }}"> Regresar</a>

