<?php

namespace App\Http\Controllers;

use App\Models\Weight;
use App\Models\User;
use App\Models\Pond;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeightController extends Controller
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
        return view('weight.create', compact('ponds', 'pond_id'));
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
            'agent'=>'required|string|max:100',//muestreo 
            'total_weight'=>'required|Numeric|min:1.0',//peso total
            'fish_number'=>'required|integer|min:1',//cantidad pesca
            //'average_weight'=>'required|Numeric|min:1.0',//peso promedio

        ];
        $mensaje = [
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);

        $dataWeight = request()->except('_token');

        //$dataWeight['number_of_fish'] = Alevin::where('user_id', '=', Auth::user()->id)->firstOrFail()->amount;
        $dataWeight['average_weight'] = round($dataWeight['total_weight']*1000/$dataWeight['fish_number'], 2); 
        $dataWeight['user_id'] = Auth::user()->id; //add request
        //dd($dataWeight);
        //$dataPiscicultor=request->all();
        $pond_id=$dataWeight['pond_id'];
        Weight::insert($dataWeight);

        //return response()->json($dataWeight);
        return redirect('weight/'.$pond_id)->with('mensaje', 'Pesajes agregada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\weight  $weight
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if (Auth::user()->rol == 'administrador'){
            $weights = Weight::where('user_id', '=', $id)->get();
            return view('weight.index', compact('weights', 'id'));
        } 
        if (Auth::user()->rol == 'piscicultor'){
            $weights = Weight::where('pond_id', '=', $id)->get();   
            $pond_id=$id;         
            return view('weight.index', compact('weights', 'id', 'pond_id'));
        } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\weight  $weight
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $weight=Weight::findOrFail($id);
        $ponds = Pond::where('user_id', '=', Auth::user()->id)->get();
        $pond_id = Weight::findOrFail($id)->pond_id;
        return view('weight.edit', compact('weight', 'ponds', 'pond_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\weight  $weight
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $campos=[
            'agent'=>'required|string|max:100',//muestreo 
            'total_weight'=>'required|Numeric|min:1.0',//peso total
            'fish_number'=>'required|integer|min:1',//cantidad pesca
            //'average_weight'=>'required|Numeric|min:1.0',//peso promedio

        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);
        
        //recolecta datos
        $dataWeight= request()->except(['_token','_method']);
        $dataWeight['average_weight'] = round($dataWeight['total_weight']*1000/$dataWeight['fish_number'], 2); 
        
        //buscamos registro con el id que pasamos y actualizamos
        Weight::where('id', '=', $id)->update( $dataWeight);
        $pond_id = Weight::findOrFail($id)->pond_id;
        $weight=Weight::findOrFail($id);
        //return view('Weight.edit', compact('Weight'));
        return redirect('weight/'.$pond_id)->with('mensaje', 'Pesajes modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\weight  $weight
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $weight=Weight::findOrFail($id);
        $pond_id = Weight::findOrFail($id)->pond_id;
        Weight::destroy($id);
        return redirect('weight/'.$pond_id)->with('mensaje', 'Pesajes borrado');
    }
}
