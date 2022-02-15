<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
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
            $data['providers'] = Provider::all();//(5);
            return view('provider.index',$data);
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
        return view('provider.create');
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
            'name'=>'required|string|max:100',
            'nit'=>'required|Numeric|digits:10',
            'city'=>'required|string|max:225',
            'phone'=>'required|Numeric|digits:10',
            'representative'=>'required|string|max:225',

        ];
        $mensaje = [
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);

        $dataProvider = request()->except('_token');

        Provider::insert($dataProvider);

        //return response()->json($dataProvider);
        return redirect('provider')->with('mensaje', 'Especie agregada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function show(Provider $provider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $provider = Provider::findOrFail($id);
        
        return view('provider.edit', compact('provider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $campos=[
            'name'=>'required|string|max:100',
            'nit'=>'required|Numeric|digits:10',
            'city'=>'required|string|max:225',
            'phone'=>'required|Numeric|digits:10',
            'representative'=>'required|string|max:225',

        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);
        
        //recolecta datos
        $dataProvider= request()->except(['_token','_method']);
        
        //buscamos registro con el id que pasamos y actualizamos
        Provider::where('id', '=', $id)->update( $dataProvider);
        $provider=Provider::findOrFail($id);
        return redirect('provider')->with('mensaje', 'Especie modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $provider=Provider::findOrFail($id);
        Provider::destroy($id);
        return redirect('provider')->with('mensaje', 'Especie borrada');
    }
}
