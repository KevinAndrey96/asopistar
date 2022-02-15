<?php

namespace App\Http\Controllers;

use App\Models\Harvest;
use App\Models\User;
use App\Models\Pond;
use App\Models\Alevin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HarvestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        if (Auth::user()->rol == 'piscicultor'){
            $data['harvests'] = Harvest::where('user_id', '=', Auth::user()->id)->get();
            return view('harvest.index',$data);
        }   */

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
        return view('harvest.create', compact('ponds', 'pond_id'));
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
            'amount'=>'required|Numeric|min:1.0',//cantidad pesca
            //'fish_number'=>'required|integer|min:1',//cantidad peces
            //'average_weight'=>'required|Numeric|min:1.0',//peso promedio
            'destination'=>'required|string|max:100',//destino, esto toca con hardcode
            'date_of_entry'=>'required|date',

        ];
        $mensaje = [
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);

        $dataHarvest = request()->except('_token');
        $dataHarvest['user_id'] = Auth::user()->id; //add request
        $dataHarvest['fish_number'] = Alevin::where('pond_id', '=', $dataHarvest['pond_id'])->firstOrFail()->amount;
        $dataHarvest['average_weight'] = round($dataHarvest['amount']*1000000/$dataHarvest['fish_number'], 2); 
        $dataHarvest['species'] = Alevin::where('pond_id', '=', $dataHarvest['pond_id'])->firstOrFail()->species; 
        //dd($dataHarvest);
        //$dataPiscicultor=request->all();
        $pond_id=$dataHarvest['pond_id'];
        Harvest::insert($dataHarvest);

        //return response()->json($dataHarvest);
        return redirect('harvest/'.$pond_id)->with('mensaje', 'Cosecha agregada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Harvest  $harvest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if (Auth::user()->rol == 'administrador'){
            $harvests = Harvest::where('user_id', '=', $id)->get();
            return view('harvest.index', compact('harvests', 'id'));
        } 
        if (Auth::user()->rol == 'piscicultor'){
            $harvests = Harvest::where('pond_id', '=', $id)->get();   
            $pond_id=$id;         
            return view('harvest.index', compact('harvests', 'pond_id'));
        } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Harvest  $harvest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $harvest=Harvest::findOrFail($id);
        $ponds = Pond::where('user_id', '=', Auth::user()->id)->get();
        $pond_id = Harvest::findOrFail($id)->pond_id;
        return view('harvest.edit', compact('harvest', 'ponds', 'pond_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Harvest  $harvest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $campos=[
            'amount'=>'required|Numeric|min:1.0',//cantidad pesca
            //'fish_number'=>'required|Numeric|min:1.0',//cantidad peces
            //'average_weight'=>'required|Numeric|min:1.0',//peso promedio
            'destination'=>'required|string|max:100',//destino
            'date_of_entry'=>'required|date',

        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);
        
        //recolecta datos
        $dataHarvest= request()->except(['_token','_method']);
        $dataHarvest['pond_id'] = Harvest::findOrFail($id)->pond_id;

        $dataHarvest['fish_number'] = Alevin::where('pond_id', '=', $dataHarvest['pond_id'])->firstOrFail()->amount;
        $dataHarvest['average_weight'] = round($dataHarvest['amount']*1000000/$dataHarvest['fish_number'], 2); 
        $dataHarvest['species'] = Alevin::where('pond_id', '=', $dataHarvest['pond_id'])->firstOrFail()->species; 
        //$dataHarvest['user_id'] = Auth::user()->id; //add request
        
        //buscamos registro con el id que pasamos y actualizamos
        Harvest::where('id', '=', $id)->update( $dataHarvest);
        $pond_id = Harvest::findOrFail($id)->pond_id;
        $harvest=Harvest::findOrFail($id);
        //return view('harvest.edit', compact('harvest'));
        return redirect('harvest/'.$pond_id)->with('mensaje', 'Cosecha modificada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Harvest  $harvest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $harvest=Harvest::findOrFail($id);
        $pond_id = Harvest::findOrFail($id)->pond_id;
        Harvest::destroy($id);
        return redirect('harvest/'.$pond_id)->with('mensaje', 'Cosecha borrada');
    }
}
