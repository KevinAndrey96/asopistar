@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/pond') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('pond.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
