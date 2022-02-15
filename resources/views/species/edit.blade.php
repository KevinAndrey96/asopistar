@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/species/'.$specie->id) }}" method="post" enctype="multipart/form-data"> 
        @csrf
        {{ method_field('PATCH') }}

        @include('species.form', ['modo'=>'Editar'])

    </form>

</div>
@endsection
