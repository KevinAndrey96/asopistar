<h1>{{ $modo }} Receta </h1>

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
    <label for="title"> Título</label>
    <input type="text" class="form-control" name="title" value="{{ isset($recipe->title)?$recipe->title:old('title') }}" id="title" placeholder="Título">
</div>

<div class="form-group">
    <label for="description"> Descripción </label>
    <input type="text" class="form-control" name="description" value="{{ isset($recipe->description)?$recipe->description:old('description') }}" id="description" placeholder="Descripción">
</div>

<div class="form-group">
    <label for="price"> Ingredientes </label>
    <textarea style="height: 100px" name="ingredients" placeholder="Ingredientes" id="ingredients" value="" class="form-control" height="50px" rows="20">{{ isset($recipe->ingredients)?$recipe->ingredients:old('ingredients') }}</textarea>
</div>

<div class="form-group">
    <label for="image"> Imagen </label>
    @if(isset($recipe->image_url))
    </br>
    <img class="img-thumbnail img-fluid" src="{{ 'https://portal.asopistar.com/'.$recipe->image_url }}" width="100" alt = "No carga">
    </br>
    @endif
    <input type="file" class="form-control" name="image" value="" id="image">
</div>

<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">

<a class="btn btn-primary" href="{{ url('/ingredients') }}"> Regresar</a>
