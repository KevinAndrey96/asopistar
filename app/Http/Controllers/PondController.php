<?php

namespace App\Http\Controllers;

use App\Models\Pond;
use App\Models\Alevin;
use App\Models\User;
use App\Models\Specie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PondController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if (Auth::user()->rol == 'piscicultor'){
            //$data['ponds'] = Pond::where('user_id', '=', Auth::user()->id)->get();
            $ponds = Pond::where('user_id', '=', Auth::user()->id)->get();
            foreach ($ponds as $pond) {
                $alevin = Alevin::where('pond_id', '=' , $pond->id)->first();
                if ($alevin){
                    $fechaAntigua  = $alevin->date_of_entry;
                    $fechaAntigua = Carbon::createFromFormat('Y-m-d', $fechaAntigua);
                    $fechaNueva  =  Carbon::now();
                    $cantidadDias = $fechaAntigua->diffInWeeks($fechaNueva);
                    $pond->age = $cantidadDias;

                    $specie = $alevin->species;
                    $youngEnd = Specie::where('species', '=', $specie)->firstOrFail()->young_end;
                    $levanteStart = Specie::where('species', '=', $specie)->firstOrFail()->levante_start;
                    $levanteEnd = Specie::where('species', '=', $specie)->firstOrFail()->levante_end ;
                    $baitStart = Specie::where('species', '=', $specie)->firstOrFail()->bait_start;

                    if ($cantidadDias >= 0 && $cantidadDias <= $youngEnd ){
                        $pond->stage = 'Cria';
                    }
                    if ($cantidadDias >= $levanteStart && $cantidadDias <= $levanteEnd ){
                        $pond->stage = 'Levante';
                    }
                    if ($cantidadDias >= $baitStart){
                        $pond->stage = 'Cebo';
                    }
                }
                else {
                    $pond->age = 'N/A';
                    $pond->stage = 'N/A';
                }
            }
            return view('pond.index', compact('ponds'));
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
        return view('pond.create');
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
            'pond_area'=>'required|Numeric|min:1.0',//area
            'water'=>'required|Numeric|min:1.0',//aforo de agua
            'tools'=>'required|string|max:225',//equipo

        ];
        $mensaje = [
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);

        $dataPond = request()->except('_token');
        $dataPond['user_id'] = Auth::user()->id; //add request                                                                                                      
        //dd($dataPond);
        //$dataPiscicultor=request->all();

        Pond::insert($dataPond);

        //return response()->json($dataPond);
        return redirect('pond')->with('mensaje', 'Estanque agregado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\pond  $pond
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        if (Auth::user()->rol == 'administrador'){
            $user=User::findOrFail($id);
            $ponds=Pond::where('user_id', '=', $id)->get();
            foreach ($ponds as $pond) {
                $alevin = Alevin::where('pond_id', '=' , $pond->id)->first();
                if ($alevin){
                    $fechaAntigua  = $alevin->date_of_entry;
                    $fechaAntigua = Carbon::createFromFormat('Y-m-d', $fechaAntigua);
                    $fechaNueva  =  Carbon::now();
                    $cantidadDias = $fechaAntigua->diffInWeeks($fechaNueva);
                    $pond->age = $cantidadDias;
                    
                    $specie = $alevin->species;
                    $especies = Specie::where('species', '=', $specie)->first();
                    $youngEnd = Specie::where('species', '=', $specie)->first()->young_end;
                    $levanteStart = Specie::where('species', '=', $specie)->firstOrFail()->levante_start;
                    $levanteEnd = Specie::where('species', '=', $specie)->firstOrFail()->levante_end ;
                    $baitStart = Specie::where('species', '=', $specie)->firstOrFail()->bait_start;

                    if ($cantidadDias >= 0 && $cantidadDias <= $youngEnd ){
                        $pond->stage = 'Cria';
                    }
                    if ($cantidadDias >= $levanteStart && $cantidadDias <= $levanteEnd ){
                        $pond->stage = 'Levante';
                    }
                    if ($cantidadDias >= $baitStart){
                        $pond->stage = 'Cebo';
                    }

                }
                else {
                    $pond->age = 'N/A';
                    $pond->stage = 'N/A';
                }
            }

            return view('pond.index', compact('user', 'ponds'));
        }  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\pond  $pond
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $pond=Pond::findOrFail($id);

        return view('pond.edit', compact('pond'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\pond  $pond
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $campos=[
            'pond_area'=>'required|Numeric|min:1.0',//area
            'water'=>'required|Numeric|min:1.0',//aforo de agua
            'tools'=>'required|string|max:225',//equipo

        ];

        $mensaje=[
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);
        
        //recolecta datos
        $dataPond= request()->except(['_token','_method']);
        //$dataPond['user_id'] = Auth::user()->id; //add request
        
        //buscamos registro con el id que pasamos y actualizamos
        Pond::where('id', '=', $id)->update( $dataPond);
        $pond=Pond::findOrFail($id);
        //return view('pond.edit', compact('pond'));
        return redirect('pond')->with('mensaje', 'Estanque modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\pond  $pond
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $pond=Pond::findOrFail($id);
        Pond::destroy($id);
        return redirect('pond')->with('mensaje', 'Estanque borrado');
    }
}
