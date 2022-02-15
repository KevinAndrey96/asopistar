@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/sanitary') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('sanitary.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
