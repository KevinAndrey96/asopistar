@extends('layouts.dashboard')

@section('content')
<div class="container">

    <form action="{{ url('/sanitary/'.$sanitary->id) }}" method="post" enctype="multipart/form-data"> 
        @csrf
        {{ method_field('PATCH') }}

        @include('sanitary.form', ['modo'=>'Editar'])

    </form>

</div>
@endsection
