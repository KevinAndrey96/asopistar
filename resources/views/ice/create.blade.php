@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/ice') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('ice.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
