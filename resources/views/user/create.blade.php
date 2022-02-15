@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/user') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('user.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
