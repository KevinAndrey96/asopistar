@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/species') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('species.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
