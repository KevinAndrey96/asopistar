@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/foodbrand/'.$foodbrand->id) }}" method="post" enctype="multipart/form-data"> 
        @csrf
        {{ method_field('PATCH') }}

        @include('foodbrand.form', ['modo'=>'Editar'])

    </form>

</div>
@endsection
