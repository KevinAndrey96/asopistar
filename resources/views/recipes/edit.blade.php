@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/recipes/'.$recipe->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        {{ method_field('PATCH') }}

        @include('recipes.form', ['modo'=>'Editar'])

    </form>

</div>
@endsection
