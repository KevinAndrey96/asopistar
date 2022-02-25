<?php

namespace App\Http\Controllers;

use App\Models\Ice;
use App\Models\User;
use App\Models\Pond;
use App\Models\Alevin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IceController extends Controller
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
        return view('ice.create', compact('ponds', 'pond_id'));
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
            'fishing_amount'=>'required|Numeric|min:1.0',//cantidad pesca
            'sacrifice_amount'=>'required|Numeric|min:1.0',//cantidad sacrificio
            'cooled_amount'=>'required|Numeric|min:1.0',//cantidad enfriado
            'transport_amount'=>'required|Numeric|min:1.0',//cantidad transporte
            'date_of_entry'=>'required|date',

        ];
        $mensaje = [
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);

        $dataIce = request()->except('_token');
        $dataIce['user_id'] = Auth::user()->id; //add request
        //-------estanque
        //$dataIce['pond_id'] = Pond::where('user_id', '=', Auth::user()->id)->firstOrFail()->id;
        $dataIce['species'] = Alevin::where('pond_id', '=', $dataIce['pond_id'])->firstOrFail()->species;
        //$dataPiscicultor=request->all();
        $pond_id=$dataIce['pond_id'];
        Ice::insert($dataIce);

        //return response()->json($dataIce);
        return redirect('ice/'.$pond_id)->with('mensaje', 'Hielo agregado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ice  $ice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if (Auth::user()->rol == 'administrador'){
            $ices = Ice::where('pond_id', '=', $id)->get();
            $userid = Pond::where('id', '=', $id)->first()->user_id;
            return view('ice.index', compact('ices', 'userid'));
        } 
        if (Auth::user()->rol == 'piscicultor'){
            $ices = Ice::where('pond_id', '=', $id)->get();   
            $pond_id=$id;         
            return view('ice.index', compact('ices', 'pond_id'));
        } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ice  $ice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $ice=Ice::findOrFail($id);
        $ponds = Pond::where('user_id', '=', Auth::user()->id)->get();
        $pond_id = Ice::findOrFail($id)->pond_id;
        return view('ice.edit', compact('ice', 'ponds', 'pond_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ice  $ice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $campos=[
            'fishing_amount'=>'required|Numeric|min:1.0',//cantidad pesca
            'sacrifice_amount'=>'required|Numeric|min:1.0',//cantidad sacrificio
            'cooled_amount'=>'required|Numeric|min:1.0',//cantidad enfriado
            'transport_amount'=>'required|Numeric|min:1.0',//cantidad transporte
            'date_of_entry'=>'required|date',

        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);
        
        //recolecta datos
        $dataIce= request()->except(['_token','_method']);
        
        $dataIce['pond_id'] = Ice::findOrFail($id)->pond_id;

        $dataIce['species'] = Alevin::where('pond_id', '=', $dataIce['pond_id'])->firstOrFail()->species;
        //buscamos registro con el id que pasamos y actualizamos
        Ice::where('id', '=', $id)->update( $dataIce);
        $pond_id = Ice::findOrFail($id)->pond_id;
        $ice=Ice::findOrFail($id);
        //return view('ice.edit', compact('ice'));
        return redirect('ice/'.$pond_id)->with('mensaje', 'Hielo modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ice  $ice
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $ice=Ice::findOrFail($id);
        $pond_id = Ice::findOrFail($id)->pond_id;
        Ice::destroy($id);
        return redirect('ice/'.$pond_id)->with('mensaje', 'Hielo borrado');
    }
}
