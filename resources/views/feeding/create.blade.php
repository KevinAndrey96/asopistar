@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/feeding') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('feeding.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
