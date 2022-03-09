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
    <input type="number" class="form-control" name="fish_number" value="{{ isset($weight->fish_number)?$weight->fish_number:0 }}" id="fish_number" min = "1" placeholder="# de peces">
</div>

<div class="form-group">
    <label> Alimento sugerido para sus peces</label>
    <input type="text" class="form-control" id = "food" value = "" readonly>
    <input type="hidden" class="form-control" id = "week" value = "{{$week}}" readonly>
    <input type="hidden" class="form-control" id = "pondFishes" value = "{{$pondFishes}}" readonly>
</div>
<script>
    $(document).ready( function () {
        calculateFood();
    } );
    function calculateFood()
    {
        const foodEstrategy = [];
        foodEstrategy[0] = 0.14;
        foodEstrategy[1] = 0.14;
        foodEstrategy[2] = 0.25;
        foodEstrategy[3] = 0.47;
        foodEstrategy[4] = 0.86;
        foodEstrategy[5] = 0.94;
        foodEstrategy[6] = 1.26;
        foodEstrategy[7] = 1.71;
        foodEstrategy[8] = 0.14;
        foodEstrategy[9] = 0.14;
        foodEstrategy[10] = 0.14;
        foodEstrategy[11] = 0.14;
        foodEstrategy[12] = 2.7;
        foodEstrategy[13] = 0.14;
        foodEstrategy[14] = 0.14;
        foodEstrategy[15] = 0.14;
        foodEstrategy[16] = 0.14;
        foodEstrategy[17] = 0.14;
        foodEstrategy[18] = 0.14;
        foodEstrategy[19] = 0.14;
        foodEstrategy[20] = 0.14;
        foodEstrategy[21] = 0.14;
        foodEstrategy[22] = 0.14;
        foodEstrategy[23] = 0.14;
        foodEstrategy[24] = 0.14;
        foodEstrategy[25] = 0.14;
        foodEstrategy[26] = 0.14;
        foodEstrategy[27] = 0.14;
        foodEstrategy[28] = 0.14;
        foodEstrategy[29] = 0.14;
        foodEstrategy[30] = 0.14;

        const proteinEstrategy = [];
        proteinEstrategy[0] = "45% Harina Inmunoaqua";
        proteinEstrategy[1] = "45% Harina Inmunoaqua";
        proteinEstrategy[2] = "45% Harina Inmunoaqua";
        proteinEstrategy[3] = "45% 1.2 mm, 1.3 mm";
        proteinEstrategy[4] = "45% 1.2 mm, 1.3 mm";
        proteinEstrategy[5] = "45% 1.2 mm, 1.3 mm";
        proteinEstrategy[6] = "45% 1.2 mm, 1.3 mm";
        proteinEstrategy[7] = "38% Inmunoaqua";
        proteinEstrategy[8] = "38% Inmunoaqua";
        proteinEstrategy[9] = "38% Inmunoaqua";
        proteinEstrategy[10] = "38% Inmunoaqua";
        proteinEstrategy[11] = "34%";
        proteinEstrategy[12] = "34%";
        proteinEstrategy[13] = "34%";
        proteinEstrategy[14] = "34%";
        proteinEstrategy[15] = "30% Pigmento";
        proteinEstrategy[16] = "30% Pigmento";
        proteinEstrategy[17] = "30% Pigmento";
        proteinEstrategy[18] = "30% Pigmento";
        proteinEstrategy[19] = "30% Pigmento";
        proteinEstrategy[20] = "30% Pigmento";
        proteinEstrategy[21] = "24% Pigmento";
        proteinEstrategy[22] = "24% Pigmento";
        proteinEstrategy[23] = "24% Pigmento";
        proteinEstrategy[24] = "24% Pigmento";
        proteinEstrategy[25] = "24% Pigmento";
        proteinEstrategy[26] = "24% Pigmento";
        proteinEstrategy[27] = "24% Pigmento";
        proteinEstrategy[28] = "24% Pigmento";
        proteinEstrategy[29] = "24% Pigmento";
        proteinEstrategy[30] = "24% Pigmento";

        var week = document.getElementById('week').value;
        var fishesNumber = document.getElementById('pondFishes').value;
        var dailyFoodWeight = foodEstrategy[week];
        var suggestedFood = dailyFoodWeight/1000*fishesNumber;
        var suggestedFood = suggestedFood.toFixed(2);
        var protein = proteinEstrategy[week];
        document.getElementById('food').value = 'Semana: '+week+', Alimento: ' + protein + ' en una cantidad sugerida de: ' + suggestedFood + ' Kg/día, para '+fishesNumber+' peces.';
    }
</script>

</br>
@if($modo=="Crear")
<input class="btn btn-success" type="submit" value="Calcular y guardar">
@else
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">
@endif

<a class="btn btn-primary" href="{{ url('/weight/'.$pond_id) }}"> Regresar</a>
</br>
</br>
<img class="img-thumbnail img-fluid" src="/tabla_italcol.jpeg" alt = "No carga">