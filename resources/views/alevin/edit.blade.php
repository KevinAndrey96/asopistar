@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/alevin/'.$alevin->id) }}" method="post" enctype="multipart/form-data"> 
        @csrf
        {{ method_field('PATCH') }}

        @include('alevin.form', ['modo'=>'Editar'])

    </form>

</div>
@endsection
