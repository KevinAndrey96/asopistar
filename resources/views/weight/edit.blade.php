@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/weight/'.$weight->id) }}" method="post" enctype="multipart/form-data"> 
        @csrf
        {{ method_field('PATCH') }}

        @include('weight.form', ['modo'=>'Editar'])

    </form>

</div>
@endsection
