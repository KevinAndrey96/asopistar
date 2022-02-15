@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/ice/'.$ice->id) }}" method="post" enctype="multipart/form-data"> 
        @csrf
        {{ method_field('PATCH') }}

        @include('ice.form', ['modo'=>'Editar'])

    </form>

</div>
@endsection
