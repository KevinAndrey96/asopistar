@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('alevin') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('alevin.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
