<h1>{{ $modo }} Usuario </h1>

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
    <label for="name"> Nombres </label>
    <input type="text" class="form-control" name="name" value="{{ isset($user->name)?$user->name:old('name') }}" id="name" placeholder="Nombres">
</div>

<div class="form-group">
    <label for="lastname"> Apellidos </label>
    <input type="text" class="form-control" name="lastname" value="{{ isset($user->lastname)?$user->lastname:old('lastname') }}" id="lastname" placeholder="Apellidos">
</div>

<div class="form-group">
    <label for="email"> Correo </label>
    <input type="text" class="form-control" name="email" value="{{ isset($user->email)?$user->email:old('email') }}" id="email" placeholder="Correo">
</div>

@if($modo=="Crear")
<div class="password">
    <label for="password" > Contraseña </label>
    <input type="password" class="form-control" name="password" value="{{ isset($user->password)?$user->password:old('password') }}" id="password" required autocomplete="new-password" placeholder="Nueva contraseña">
</div>
@endif

@if($modo=="Crear")
<div>
    <label for="rol"> Rol </label>
    <select id="rol" name="rol" class="form-select" aria-label="Default select example" onchange = "hiddenText()">
        <option value="piscicultor"  {{ old('rol') == 'piscicultor' ? 'selected' : '' }}>
            Piscicultor
        </option>
        <option value="administrador" {{ old('rol') == 'administrador' ? 'selected' : '' }}>
            Administrador
        </option>
    </select>
</div><!--//col-6-->
<script>
    $(document).ready( function () {
        hiddenText();
    } );
    function hiddenText()
    {
        let unit_number_label = document.getElementById("unit_number_label");
        let estate_label = document.getElementById("estate_label");
        let sidewalk_label = document.getElementById("sidewalk_label");
        let hidden_unit_number = unit_number_label.getAttribute("hidden");  
        let hidden_etate = estate_label.getAttribute("hidden");  
        let hidden_sidewalk = sidewalk_label.getAttribute("hidden");  
        if(document.getElementById('rol').value == "administrador"){
            document.getElementById("unit_number").type = "hidden";
            unit_number_label.setAttribute("hidden", "hidden");
            document.getElementById("estate").type = "hidden";
            estate_label.setAttribute("hidden", "hidden");
            document.getElementById("sidewalk").type = "hidden";
            sidewalk_label.setAttribute("hidden", "hidden");
        }else{
            document.getElementById("unit_number").type = "text";
            unit_number_label.removeAttribute("hidden");
            document.getElementById("estate").type = "text";
            estate_label.removeAttribute("hidden");
            document.getElementById("sidewalk").type = "text";
            sidewalk_label.removeAttribute("hidden");
        }
    }
</script>
@endif

@if($modo=="Crear") 
    <div class="form-group">
        <label for="unit_number" id="unit_number_label"> # Unidad </label>
        <input type="text" class="form-control" name="unit_number" value="{{ isset($user->unit_number)?$user->unit_number:old('unit_number') }}" id="unit_number" placeholder="Código Productor">
    </div>
@endif

@if($modo=="Editar") 
    @if($user->rol == 'piscicultor') 
    <div class="form-group">
        <label for="unit_number"> # Unidad </label>
        <input type="text" class="form-control" name="unit_number" value="{{ isset($user->unit_number)?$user->unit_number:old('unit_number') }}" id="unit_number" placeholder="Código Productor">
    </div>
    @endif
@endif


<div class="form-group">
    <label for="code"> Cédula </label>
    <input type="text" class="form-control" name="code" value="{{ isset($user->code)?$user->code:old('code') }}" id="code" placeholder="Cedula">
</div>

@if($modo=="Crear") 
<div>
    <label for="gender"> Genero </label>
    <select id="gender" name="gender" class="form-select" aria-label="Default select example">
        <option value="masculino"  {{ old('gender') == 'masculino' ? 'selected' : '' }}>
            Masculino
        </option>
        <option value="femenino" {{ old('gender') == 'femenino' ? 'selected' : '' }}>
            Femenino
        </option>
    </select>
</div><!--//col-6-->
@endif

@if($modo=="Crear") 
    <div class="form-group">
        <label for="estate" id="estate_label"> Predio </label>
        <input type="text" class="form-control" name="estate" value="{{ isset($user->estate)?$user->estate:old('estate') }}" id="estate" placeholder="Predio">
    </div>

    <div class="form-group">
        <label for="sidewalk" id="sidewalk_label"> Vereda </label>
        <input type="text" class="form-control" name="sidewalk" value="{{ isset($user->sidewalk)?$user->sidewalk:old('sidewalk') }}" id="sidewalk" placeholder="Vereda">
    </div>
@endif

@if($modo=="Editar") 
    @if($user->rol == 'piscicultor')
        <div class="form-group">
            <label for="estate"> Predio </label>
            <input type="text" class="form-control" name="estate" value="{{ isset($user->estate)?$user->estate:old('estate') }}" id="estate" placeholder="Predio">
        </div>

        <div class="form-group">
            <label for="sidewalk"> Vereda </label>
            <input type="text" class="form-control" name="sidewalk" value="{{ isset($user->sidewalk)?$user->sidewalk:old('sidewalk') }}" id="sidewalk" placeholder="Vereda">
        </div>
    @endif
@endif

<div class="form-group">
    <label for="phone"> Telefono</label>
    <input type="number" class="form-control" name="phone" value="{{ isset($user->phone)?$user->phone:old('phone') }}" id="phone" min="1" placeholder="3000000000">
</div>


<div class="form-group">
    <input type="hidden" class="form-control" name="updatepass" value="0" id="updatepass" readonly="readonly">
</div>
<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">

@if(Auth::user()->rol == 'administrador') 
<a class="btn btn-primary" href="{{ url('/admin') }}"> Regresar</a>
@endif
@if(Auth::user()->rol == 'piscicultor')
<a class="btn btn-primary" href="{{ url('/home') }}"> Regresar</a>
@endif
