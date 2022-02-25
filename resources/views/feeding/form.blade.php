<h1>{{ $modo }} registro de alimentación </h1>

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
    <input type="number" class="form-control" name="amount" value="{{ isset($feeding->amount)?$feeding->amount:old('amount') }}" id="amount" step="0.01" min="1.0" placeholder="Cantidad">
</div>

<div>
    <label for="mark"> Marca </label>
    <select id="mark" name="mark" class="form-select" aria-label="Default select example">
        @foreach( $foodbrands as $foodbrand )
            <option value="{{ $foodbrand->id }}" > {{ $foodbrand->name }} ({{ $foodbrand->protein }}%)</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="date_of_entry"> Fecha </label>
    <input type="date" class="form-control" name="date_of_entry" value="{{ isset($feeding->date_of_entry)?$feeding->date_of_entry:old('date_of_entry') }}" id="date_of_entry" placeholder="aa/mm/dd">
</div>
<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">

<a class="btn btn-primary" href="{{ url('/feeding/'.$pond_id) }}"> Regresar</a>
