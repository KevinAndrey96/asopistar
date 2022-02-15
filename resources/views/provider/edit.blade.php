@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/provider/'.$provider->id) }}" method="post" enctype="multipart/form-data"> 
        @csrf
        {{ method_field('PATCH') }}

        @include('provider.form', ['modo'=>'Editar'])

    </form>

</div>
@endsection
