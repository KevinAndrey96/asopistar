<h1>{{ $modo }} suministro de hielo </h1>

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
    <label for="fishing_amount"> Pesca Kg</label>
    <input type="number" class="form-control" name="fishing_amount" value="{{ isset($ice->fishing_amount)?$ice->fishing_amount:old('fishing_amount') }}" id="fishing_amount" step="0.01" min = "1.0" placeholder="Cantidad">
</div>

<div class="form-group">
    <label for="sacrifice_amount"> Cantidad de hielo de sacrificio kg</label>
    <input type="number" class="form-control" name="sacrifice_amount" value="{{ isset($ice->sacrifice_amount)?$ice->sacrifice_amount:old('sacrifice_amount') }}" id="sacrifice_amount" step="0.01" min = "1.0" placeholder="Cantidad">
</div>

<div class="form-group">
    <label for="cooled_amount"> Cantidad de hielo de enfriado kg</label>
    <input type="number" class="form-control" name="cooled_amount" value="{{ isset($ice->cooled_amount)?$ice->cooled_amount:old('cooled_amount') }}" id="cooled_amount" step="0.01" min = "1.0" placeholder="Cantidad">
</div>

<div class="form-group">
    <label for="transport_amount"> Cantidad de hielo de transporte kg</label>
    <input type="number" class="form-control" name="transport_amount" value="{{ isset($ice->transport_amount)?$ice->transport_amount:old('transport_amount') }}" id="transport_amount" step="0.01" min = "1.0" placeholder="Cantidad">
</div>

<div class="form-group">
    <label for="date_of_entry"> Fecha </label>
    <input type="date" class="form-control" name="date_of_entry" value="{{ isset($ice->date_of_entry)?$ice->date_of_entry:old('date_of_entry') }}" id="date_of_entry" placeholder="aa/mm/dd">
</div>
<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">

<a class="btn btn-primary" href="{{ url('/ice/'.$pond_id) }}"> Regresar</a>
