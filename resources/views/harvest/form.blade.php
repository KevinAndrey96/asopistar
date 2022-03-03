<h1>{{ $modo }} registro de cosecha </h1>

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

<div class="form-group">
    <label for="amount"> Cantidad Kg</label>
    <input type="number" class="form-control" name="amount" value="{{ isset($harvest->amount)?$harvest->amount:old('amount') }}" id="amount" step="0.01" min = "1.0" placeholder="Cantidad kg">
</div>

<div>
    <label for="destination"> Destino</label>
    <select id="destination" name="destination" class="form-select" aria-label="Default select example">
            <option value="Planta Asopistar Tibu" > Planta Asopistar Tarra </option>
    </select>
</div>

<div class="form-group">
    <label for="date_of_entry"> Fecha </label>
    <input type="date" class="form-control" name="date_of_entry" value="{{ isset($harvest->date_of_entry)?$harvest->date_of_entry:old('date_of_entry') }}" id="date_of_entry" placeholder="aa/mm/dd">
</div>
<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">

<a class="btn btn-primary" href="{{ url('/harvest/'.$pond_id) }}"> Regresar</a>
