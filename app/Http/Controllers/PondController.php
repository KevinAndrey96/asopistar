<?php

namespace App\Http\Controllers;

use App\Models\Pond;
use App\Models\Alevin;
use App\Models\User;
use App\Models\Feeding;
use App\Models\Ice;
use App\Models\Harvest;
use App\Models\Weight;
use App\Models\Sanitary;
use App\Models\Specie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;    

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
            $ponds = Pond::where([['user_id', '=', Auth::user()->id],['is_enabled', '=', '1']])->get();
            foreach ($ponds as $pond) {
                $alevin = Alevin::where('pond_id', '=' , $pond->id)->first();
                //dd($alevin);
                if ($alevin){
                    $fechaAntigua  = $alevin->date_of_entry;
                    $fechaAntigua = Carbon::createFromFormat('Y-m-d', $fechaAntigua);
                    $fechaNueva  =  Carbon::now();
                    $cantidadDias = $fechaAntigua->diffInWeeks($fechaNueva);
                    $pond->age = $cantidadDias;

                    $specie = $alevin->species;
                    if(Specie::where('species', '=', $specie)->first()){
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
                    }else{
                        $pond->stage = $specie.' fue borrada';
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

    public function report($id)
    {
        //
        if (Auth::user()->rol == 'piscicultor'){
            $user=User::findOrFail(Auth::user()->id);
            $user->date = Carbon::now()->format('Y-m-d');
            $user->iceSacrificecount = 0;
            $user->iceCooledcount = 0;
            $user->iceTransportcount = 0;
            $user->foodcount = 0;
            $ponds=Pond::where('id', '=', $id)->get();
            foreach ($ponds as $pond) {
                $alevin = Alevin::where('pond_id', '=' , $pond->id)->first();
                $pondcode = $pond->pondcode;
                if ($alevin){
                    $pond->alevinExist = '1';
                    $fechaAntigua  = $alevin->date_of_entry;
                    $fechaAntigua = Carbon::createFromFormat('Y-m-d', $fechaAntigua);
                    $fechaNueva  =  Carbon::now();
                    $cantidadDias = $fechaAntigua->diffInWeeks($fechaNueva);
                    $pond->age = $cantidadDias;
                    
                    $specie = $alevin->species;
                    if(Specie::where('species', '=', $specie)->first()){
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
                    }else{
                        $pond->stage = $specie.' fue borrada';
                    }
                    
                    $foods = Feeding::where('pond_id', '=' , $pond->id)->get();
                    foreach($foods as $food){
                        $user->foodcount = $user->foodcount + $food->amount;
                    }
                    $ices = Ice::where('pond_id', '=' , $pond->id)->get();
                    foreach($ices as $ice){
                        $user->iceSacrificecount = $user->iceSacrificecount + $ice->sacrifice_amount;
                        $user->iceCooledcount = $user->iceCooledcount + $ice->cooled_amount;
                        $user->iceTransportcount = $user->iceTransportcount + $ice->transport_amount;
                    }
                    
                    $feedings = Feeding::where('pond_id', '=' , $pond->id)->get();
                    $ices = Ice::where('pond_id', '=' , $pond->id)->get();
                    $harvests = Harvest::where('pond_id', '=' , $pond->id)->get();
                    $weights = Weight::where('pond_id', '=' , $pond->id)->get();
                    $sanitaries = Sanitary::where('pond_id', '=' , $pond->id)->get(); 

                }
                else {
                    $pond->alevinExist = '0';
                    $pond->age = 'N/A';
                    $pond->stage = 'N/A';

                    $feedings = Feeding::where('pond_id', '=' , $pond->id)->get();
                    $ices = Ice::where('pond_id', '=' , $pond->id)->get();
                    $harvests = Harvest::where('pond_id', '=' , $pond->id)->get();
                    $weights = Weight::where('pond_id', '=' , $pond->id)->get();
                    $sanitaries = Sanitary::where('pond_id', '=' , $pond->id)->get();
                }
            }
            $user->title = 'Reporte: Estanque_'.$pondcode.' '.$user->name.' '.$user->lastname.' '.$user->date;
            $pdf = PDF::loadView('pond.report', ['user'=>$user, 'ponds'=>$ponds, 'alevin'=>$alevin, 'feedings'=>$feedings, 'ices'=>$ices, 'harvests'=>$harvests, 'weights'=>$weights, 'sanitaries'=>$sanitaries]);//->setPaper('letter', 'landscape');
            $pdf->download($user->title.'.pdf');
            return $pdf->stream($user->title.'.pdf');
            //return view('pond.index', compact('user', 'ponds'));
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
            'pondcode'=>'required',
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
            'pondcode'=>'required',
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
        //$pond=Pond::findOrFail($id);
        //Pond::destroy($id);
        //return redirect('pond')->with('mensaje', 'Estanque borrado');

        if (Auth::user()->rol == 'piscicultor'){
            $user=User::findOrFail(Auth::user()->id);
            $user->date = Carbon::now()->format('Y-m-d');
            $user->foodcount = 0;
            $ponds=Pond::where('id', '=', $id)->get();
            foreach ($ponds as $pond) {
                $alevin = Alevin::where('pond_id', '=' , $pond->id)->first();
                $pondcode = $pond->pondcode;
                if ($alevin){
                    $pond->alevinExist = '1';
                    $fechaAntigua  = $alevin->date_of_entry;
                    $fechaAntigua = Carbon::createFromFormat('Y-m-d', $fechaAntigua);
                    $fechaNueva  =  Carbon::now();
                    $cantidadDias = $fechaAntigua->diffInWeeks($fechaNueva);
                    $pond->age = $cantidadDias;
                    
                    $specie = $alevin->species;
                    if(Specie::where('species', '=', $specie)->first()){
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
                    }else{
                        $pond->stage = $specie.' fue borrada';
                    }

                    $foods = Feeding::where('pond_id', '=' , $pond->id)->get();
                    foreach($foods as $food){
                        $user->foodcount = $user->foodcount + $food->amount;
                    }

                    $feedings = Feeding::where('pond_id', '=' , $pond->id)->get();
                    $ices = Ice::where('pond_id', '=' , $pond->id)->get();
                    $harvests = Harvest::where('pond_id', '=' , $pond->id)->get();
                    $weights = Weight::where('pond_id', '=' , $pond->id)->get();
                    $sanitaries = Sanitary::where('pond_id', '=' , $pond->id)->get(); 

                }
                else {
                    $pond->alevinExist = '0';
                    $pond->age = 'N/A';
                    $pond->stage = 'N/A';

                    $feedings = Feeding::where('pond_id', '=' , $pond->id)->get();
                    $ices = Ice::where('pond_id', '=' , $pond->id)->get();
                    $harvests = Harvest::where('pond_id', '=' , $pond->id)->get();
                    $weights = Weight::where('pond_id', '=' , $pond->id)->get();
                    $sanitaries = Sanitary::where('pond_id', '=' , $pond->id)->get();
                }
            }
            $pond=Pond::findOrFail($id);
            $newPond = $pond->replicate();
            $newPond->created_at = Carbon::now();
            $newPond->save();

            $user->title = 'Reporte: Estanque_'.$pondcode.' '.$user->name.' '.$user->lastname.' '.$user->date;
            $dataPond['is_enabled'] = 0;
            Pond::where('id', '=', $id)->update( $dataPond);
            $pond=Pond::findOrFail($id);

            $pdf = PDF::loadView('pond.report', ['user'=>$user, 'ponds'=>$ponds, 'alevin'=>$alevin, 'feedings'=>$feedings, 'ices'=>$ices, 'harvests'=>$harvests, 'weights'=>$weights, 'sanitaries'=>$sanitaries]);
            //$pdf->save(storage_path().'/pdf/'.$user->title.'.pdf');//stream($user->title.'.pdf');
            return $pdf->stream($user->title.'.pdf');
            //return redirect('pond')->with('mensaje', 'Estanque borrado');
        }
    }
}
