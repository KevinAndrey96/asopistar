@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/harvest') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('harvest.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
