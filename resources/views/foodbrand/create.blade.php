@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/foodbrand') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('foodbrand.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
