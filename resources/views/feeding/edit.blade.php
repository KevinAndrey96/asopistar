@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/feeding/'.$feeding->id) }}" method="post" enctype="multipart/form-data"> 
        @csrf
        {{ method_field('PATCH') }}

        @include('feeding.form', ['modo'=>'Editar'])

    </form>

</div>
@endsection
