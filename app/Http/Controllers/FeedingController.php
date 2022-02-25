<?php

namespace App\Http\Controllers;

use App\Models\Feeding;
use App\Models\User;
use App\Models\Pond;
use App\Models\Alevin;
use App\Models\Specie;
use App\Models\Foodbrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FeedingController extends Controller
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
        if (Auth::user()->rol == 'piscicultor'){
            $ponds = Pond::where('user_id', '=', Auth::user()->id)->get();
            $foodbrands = Foodbrand::all();
            return view('feeding.create', compact('ponds', 'foodbrands', 'pond_id'));
        }
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
            'amount'=>'required|Numeric|min:1.0',//cantidad
            'mark'=>'required|string|max:200',//marca
            //'protein'=>'required|Numeric|min:1.0|max:100',//proteina
            'date_of_entry'=>'required|date',

        ];
        $mensaje = [
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);

        $dataFeeding = request()->except('_token');

        $fechaAntigua  = Alevin::where('pond_id', '=', $dataFeeding['pond_id'])->firstOrFail()->date_of_entry;
        $fechaAntigua = Carbon::createFromFormat('Y-m-d', $fechaAntigua);
        $fechaNueva  =  Carbon::now();
        $cantidadDias = $fechaAntigua->diffInWeeks($fechaNueva);
        $dataFeeding['age'] = $cantidadDias;

        $dataFeeding['number_of_fish'] = Alevin::where('pond_id', '=', $dataFeeding['pond_id'])->firstOrFail()->amount;
        $dataFeeding['average_weight'] = round($dataFeeding['amount'] * 1000 / $dataFeeding['number_of_fish'],2); 
        $dataFeeding['user_id'] = Auth::user()->id; //add request

        $specie = Alevin::where('pond_id', '=', $dataFeeding['pond_id'])->firstOrFail()->species;
        $youngEnd = Specie::where('species', '=', $specie)->firstOrFail()->young_end;
        $levanteStart = Specie::where('species', '=', $specie)->firstOrFail()->levante_start;
        $levanteEnd = Specie::where('species', '=', $specie)->firstOrFail()->levante_end ;
        $baitStart = Specie::where('species', '=', $specie)->firstOrFail()->bait_start;

        if ($cantidadDias >= 0 && $cantidadDias <= $youngEnd ){
            $dataFeeding['stage'] = 'Cria';
        }
        if ($cantidadDias >= $levanteStart && $cantidadDias <= $levanteEnd ){
            $dataFeeding['stage'] = 'Levante';
        }
        if ($cantidadDias >= $baitStart){
            $dataFeeding['stage'] = 'Cebo';
        }

        $dataFeeding['protein'] = Foodbrand::where('id', '=', $dataFeeding['mark'])->firstOrFail()->protein;
        $dataFeeding['mark'] = Foodbrand::where('id', '=', $dataFeeding['mark'])->firstOrFail()->name;

        $pond_id=$dataFeeding['pond_id'];
        Feeding::insert($dataFeeding);

        //return response()->json($dataFeeding);
        return redirect('feeding/'.$pond_id)->with('mensaje', 'Alimentación agregado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feeding  $feeding
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if (Auth::user()->rol == 'administrador'){
            $feedings = Feeding::where('pond_id', '=', $id)->get();
            $userid = Pond::where('id', '=', $id)->first()->user_id;
            return view('feeding.index', compact('feedings', 'userid'));
        }  
        if (Auth::user()->rol == 'piscicultor'){
            $feedings = Feeding::where('pond_id', '=', $id)->get();     
            $pond_id=$id;         
            return view('feeding.index', compact('feedings', 'pond_id'));
        } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feeding  $feeding
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        if (Auth::user()->rol == 'piscicultor'){
            $feeding = Feeding::findOrFail($id);
            $ponds = Pond::where('user_id', '=', Auth::user()->id)->get();
            $foodbrands = Foodbrand::all();
            $pond_id = Feeding::findOrFail($id)->pond_id;
            return view('feeding.edit', compact('feeding', 'ponds', 'foodbrands', 'pond_id'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feeding  $feeding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $campos=[
            //'pond_id'=>'required',
            'amount'=>'required|Numeric|min:1.0',//cantidad
            //'stage'=>'required|string|max:100',//etapa
            'mark'=>'required|string|max:200',//marca
            //'protein'=>'required|Numeric|min:1.0|max:100',//proteina
            'date_of_entry'=>'required|date',

        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);
        
        //recolecta datos
        $dataFeeding= request()->except(['_token','_method']);

        $dataFeeding['pond_id'] = Feeding::findOrFail($id)->pond_id;

        $fechaAntigua  = Alevin::where('pond_id', '=', $dataFeeding['pond_id'])->firstOrFail()->date_of_entry;
        $fechaAntigua = Carbon::createFromFormat('Y-m-d', $fechaAntigua);
        $fechaNueva  =  Carbon::now();
        $cantidadDias = $fechaAntigua->diffInWeeks($fechaNueva);
        $dataFeeding['age'] = $cantidadDias;

        $dataFeeding['number_of_fish'] = Alevin::where('pond_id', '=', $dataFeeding['pond_id'])->firstOrFail()->amount;
        $dataFeeding['average_weight'] = round($dataFeeding['amount'] * 1000 / $dataFeeding['number_of_fish'],2); 

        $specie = Alevin::where('pond_id', '=', $dataFeeding['pond_id'])->firstOrFail()->species;
        $youngEnd = Specie::where('species', '=', $specie)->firstOrFail()->young_end;
        $levanteStart = Specie::where('species', '=', $specie)->firstOrFail()->levante_start;
        $levanteEnd = Specie::where('species', '=', $specie)->firstOrFail()->levante_end ;
        $baitStart = Specie::where('species', '=', $specie)->firstOrFail()->bait_start;

        if ($cantidadDias >= 0 && $cantidadDias <= $youngEnd ){
            $dataFeeding['stage'] = 'Cria';
        }
        if ($cantidadDias >= $levanteStart && $cantidadDias <= $levanteEnd ){
            $dataFeeding['stage'] = 'Levante';
        }
        if ($cantidadDias >= $baitStart){
            $dataFeeding['stage'] = 'Cebo';
        }
        $dataFeeding['protein'] = Foodbrand::where('id', '=', $dataFeeding['mark'])->firstOrFail()->protein;
        $dataFeeding['mark'] = Foodbrand::where('id', '=', $dataFeeding['mark'])->firstOrFail()->name;
        
        
        //buscamos registro con el id que pasamos y actualizamos
        Feeding::where('id', '=', $id)->update( $dataFeeding);
        $pond_id = Feeding::findOrFail($id)->pond_id;
        $feeding=Feeding::findOrFail($id);
        return redirect('feeding/'.$pond_id)->with('mensaje', 'Alimentación modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feeding  $feeding
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if (Auth::user()->rol == 'piscicultor'){
            $feeding=Feeding::findOrFail($id);
            $pond_id = Feeding::findOrFail($id)->pond_id;
            Feeding::destroy($id);
            return redirect('feeding/'.$pond_id)->with('mensaje', 'Alimentación borrado');
        }
    }
}
