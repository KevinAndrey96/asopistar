@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/pond/'.$pond->id) }}" method="post" enctype="multipart/form-data"> 
        @csrf
        {{ method_field('PATCH') }}

        @include('pond.form', ['modo'=>'Editar'])

    </form>

</div>
@endsection
