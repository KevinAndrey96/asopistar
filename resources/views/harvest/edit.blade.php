@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/harvest/'.$harvest->id) }}" method="post" enctype="multipart/form-data"> 
        @csrf
        {{ method_field('PATCH') }}

        @include('harvest.form', ['modo'=>'Editar'])

    </form>

</div>
@endsection
