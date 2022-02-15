@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/weight') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('weight.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
