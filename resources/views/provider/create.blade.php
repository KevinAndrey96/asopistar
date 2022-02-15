@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/provider') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('provider.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
