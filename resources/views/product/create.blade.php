@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/product') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('product.form', ['modo'=>'Crear'])
    </form>

</div>
@endsection
