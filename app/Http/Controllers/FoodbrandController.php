<?php

namespace App\Http\Controllers;

use App\Models\Foodbrand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodbrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (Auth::user()->rol == 'administrador'){
            $data['foodbrands'] = Foodbrand::all();//(5);
            return view('foodbrand.index',$data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('foodbrand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $campos=[
            'name'=>'required|string|max:225',
            'protein'=>'required|Numeric|min:1.0|max:99.9',

        ];
        $mensaje = [
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);

        $dataFoodbrand = request()->except('_token');

        Foodbrand::insert($dataFoodbrand);

        //return response()->json($dataFoodbrand);
        return redirect('foodbrand')->with('mensaje', 'Marca agregada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Foodbrand  $foodbrand
     * @return \Illuminate\Http\Response
     */
    public function show(Foodbrand $foodbrand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Foodbrand  $foodbrand
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $foodbrand = Foodbrand::findOrFail($id);
        
        return view('foodbrand.edit', compact('foodbrand'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Foodbrand  $foodbrand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $campos=[
            'name'=>'required|string|max:225',
            'protein'=>'required|Numeric|min:1.0|max:99.9',

        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);
        
        //recolecta datos
        $dataFoodbrand= request()->except(['_token','_method']);
        
        //buscamos registro con el id que pasamos y actualizamos
        Foodbrand::where('id', '=', $id)->update( $dataFoodbrand);
        $foodbrand=Foodbrand::findOrFail($id);
        return redirect('foodbrand')->with('mensaje', 'Marca modificada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Foodbrand  $foodbrand
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $foodbrand=Foodbrand::findOrFail($id);
        Foodbrand::destroy($id);
        return redirect('foodbrand')->with('mensaje', 'Marca borrada');
    }
}
