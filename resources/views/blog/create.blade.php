@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/blog') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('blog.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
