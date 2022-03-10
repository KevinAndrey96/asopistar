@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/recipes') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('recipes.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
