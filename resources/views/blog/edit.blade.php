@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/blog/'.$blog->id) }}" method="post" enctype="multipart/form-data"> 
        @csrf
        {{ method_field('PATCH') }}

        @include('blog.form', ['modo'=>'Editar'])

    </form>

</div>
@endsection
