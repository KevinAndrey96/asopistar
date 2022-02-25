<h1>{{ $modo }} Producto </h1>

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
    <label for="name"> Nombre</label>
    <input type="text" class="form-control" name="name" value="{{ isset($product->name)?$product->name:old('name') }}" id="name" placeholder="Nombre">
</div>

<div class="form-group">
    <label for="description"> Descripción </label>
    <input type="text" class="form-control" name="description" value="{{ isset($product->description)?$product->description:old('description') }}" id="description" placeholder="Descripción">
</div>

<div class="form-group">
    <label for="price"> Precio </label>
    <input type="number" class="form-control" name="price" value="{{ isset($product->price)?$product->price:old('price') }}" id="price" min="1" placeholder="1">
</div>

<div class="form-group">
    <label for="image"> Imagen </label>
    @if(isset($product->image_url))
    </br>
    <img class="img-thumbnail img-fluid" src="{{ 'https://portal.asopistar.com/'.$product->image_url }}" width="100" alt = "No carga">
    </br>
    @endif
    <input type="file" class="form-control" name="image" value="" id="image">
</div>

<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">

<a class="btn btn-primary" href="{{ url('/product') }}"> Regresar</a>
