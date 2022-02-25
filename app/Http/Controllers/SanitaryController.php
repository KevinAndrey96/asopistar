<?php

namespace App\Http\Controllers;

use App\Models\Sanitary;
use App\Models\User;
use App\Models\Pond;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SanitaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($pond_id)
    {
        //
        $ponds = Pond::where('user_id', '=', Auth::user()->id)->get();
        return view('sanitary.create', compact('ponds', 'pond_id'));
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
            'pond_id'=>'required',
            'agent'=>'required|string|max:100',//agentes 
            'description'=>'required|string|max:225',//descripcion

        ];
        $mensaje = [
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);

        $dataSanitary = request()->except('_token');
        $dataSanitary['user_id'] = Auth::user()->id; //add request
        //dd($dataSanitary);
        //$dataPiscicultor=request->all();
        $pond_id=$dataSanitary['pond_id'];
        Sanitary::insert($dataSanitary);

        //return response()->json($dataSanitary);
        return redirect('sanitary/'.$pond_id)->with('mensaje', 'Registro sanitario agregada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sanitary  $sanitary
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if (Auth::user()->rol == 'administrador'){
            $sanitaries = Sanitary::where('pond_id', '=', $id)->get();
            $userid = Pond::where('id', '=', $id)->first()->user_id;
            return view('sanitary.index', compact('sanitaries', 'userid'));
        } 
        if (Auth::user()->rol == 'piscicultor'){
            $sanitaries = Sanitary::where('pond_id', '=', $id)->get();   
            $pond_id=$id;         
            return view('sanitary.index', compact('sanitaries', 'pond_id'));
        } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sanitary  $sanitary
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //        
        $sanitary=Sanitary::findOrFail($id);
        $ponds = Pond::where('user_id', '=', Auth::user()->id)->get();
        $pond_id = Sanitary::findOrFail($id)->pond_id;
        return view('sanitary.edit', compact('sanitary', 'ponds', 'pond_id'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sanitary  $sanitary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $campos=[
            'agent'=>'required|string|max:100',//agentes 
            'description'=>'required|string|max:225',//descripcion

        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);
        
        //recolecta datos
        $dataSanitary= request()->except(['_token','_method']);
        //$dataSanitary['user_id'] = Auth::user()->id; //add request
        
        //buscamos registro con el id que pasamos y actualizamos
        Sanitary::where('id', '=', $id)->update( $dataSanitary);
        $pond_id = Sanitary::findOrFail($id)->pond_id;
        $sanitary=Sanitary::findOrFail($id);
        //return view('sanitary.edit', compact('sanitary'));
        return redirect('sanitary/'.$pond_id)->with('mensaje', 'Registro sanitario modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sanitary  $sanitary
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $sanitary=Sanitary::findOrFail($id);
        $pond_id = Sanitary::findOrFail($id)->pond_id;
        Sanitary::destroy($id);
        return redirect('sanitary/'.$pond_id)->with('mensaje', 'Registro sanitario borrado');
    }
}
