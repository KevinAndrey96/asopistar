<?php

namespace App\Http\Controllers;

use App\Models\Alevin;
use App\Models\User;
use App\Models\Pond;
use App\Models\Specie;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlevinController extends Controller
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
        $ponds = Pond::where('user_id', '=', Auth::user()->pond_id)->get();
        $species = Specie::all();
        $providers = Provider::all();
        return view('alevin.create', compact('ponds', 'species', 'providers', 'pond_id'));
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
            'pond_id'=>'required|unique:alevins',
            'species'=>'required|string|max:100',
            'amount'=>'required|integer|min:1',
            'source'=>'required|string|max:200',
            'date_of_entry'=>'required|date',

        ];
        $mensaje = [
            'pond_id.unique'=>'Ya existe un registro de alevines en este estanque',
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);

        $dataAlevin = request()->except('_token');
        $dataAlevin['user_id'] = Auth::user()->id; //add request
        $pond_id=$dataAlevin['pond_id'];
        Alevin::insert($dataAlevin);        
        //return response()->json($dataAlevin);
        return redirect('alevin/'.$pond_id)->with('mensaje', 'Alevin agregado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alevin  $alevin
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if (Auth::user()->rol == 'administrador'){
            $alevins = Alevin::where('user_id', '=', $id)->get();
            return view('alevin.index', compact('alevins', 'id'));
        } 
        if (Auth::user()->rol == 'piscicultor'){
            $alevins = Alevin::where('pond_id', '=', $id)->get();   
            $pond_id=$id;         
            return view('alevin.index', compact('alevins', 'pond_id'));
        }    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Alevin  $alevin
     * @return \Illuminate\Http\Response
     */
    public function edit($id)//Alevin $alevin)
    {
        //
        $alevin=Alevin::findOrFail($id);
        $ponds = Pond::where('user_id', '=', Auth::user()->id)->get();
        $species = Specie::all();
        $providers = Provider::all();
        $pond_id = Alevin::findOrFail($id)->pond_id;
        return view('alevin.edit', compact('alevin', 'ponds', 'species', 'providers', 'pond_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Alevin  $alevin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)// Alevin $alevin)
    {
        //
        $campos=[
            //'pond_id'=>'required',
            'species'=>'required|string|max:100',
            'amount'=>'required|integer|min:1',
            'source'=>'required|string|max:200',
            'date_of_entry'=>'required|date',

        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);
        
        //recolecta datos
        $dataAlevin= request()->except(['_token','_method']);
        
        //buscamos registro con el id que pasamos y actualizamos
        Alevin::where('id', '=', $id)->update( $dataAlevin);
        $pond_id = Alevin::findOrFail($id)->pond_id;
        $alevin=Alevin::findOrFail($id);
        return redirect('alevin/'.$pond_id)->with('mensaje', 'Alevin modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alevin  $alevin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)//Alevin $alevin)
    {
        //
        $alevin=Alevin::findOrFail($id);
        $pond_id = Alevin::findOrFail($id)->pond_id;
        Alevin::destroy($id);
        return redirect('alevin/'.$pond_id)->with('mensaje', 'Alevin borrado');
    }
}
