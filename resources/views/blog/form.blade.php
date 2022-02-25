<h1>{{ $modo }} Blog </h1>

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
    <label for="title"> Título </label>
    <input type="text" class="form-control" name="title" value="{{ isset($blog->title)?$blog->title:old('title') }}" id="title" placeholder="Título">
</div>

<div class="form-group">
    <label for="date"> Fecha </label>
    <input type="date" class="form-control" name="date" value="{{ isset($blog->date)?$blog->date:old('date') }}" id="date" placeholder="aa/mm/dd">
</div>

<div class="form-group">
    <label for="description"> Descripción </label>
    <input type="text" class="form-control" name="description" value="{{ isset($blog->description)?$blog->description:old('description') }}" id="description" placeholder="Descripción">
</div>

<div class="form-group">
    <label for="content"> Contenido </label>
    <textarea class="ckeditor form-control" name="content" value="" id="content" placeholder="Contenido">{{ isset($blog->content)?$blog->content:old('content') }}</textarea>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
        //$('.ckeditor').setData("{{ isset($blog->content)?$blog->content:old('content') }}");
    });
</script>

<div class="form-group">
    <label for="image"> Imagen </label>
    @if(isset($blog->image_url))
    </br>
    <img class="img-thumbnail img-fluid" src="{{ 'https://portal.asopistar.com/'.$blog->image_url }}" width="100" alt = "No carga">
    </br>
    @endif
    <input type="file" class="form-control" name="image" value="" id="image">
</div>

<br>
<input class="btn btn-success" type="submit" value="{{ $modo }} datos">

<a class="btn btn-primary" href="{{ url('/blog') }}"> Regresar</a>
