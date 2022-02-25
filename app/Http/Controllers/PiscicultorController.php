<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Pond;
use App\Models\Alevin;
use App\Models\Feeding;
use App\Models\Sanitary;
use App\Models\Specie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;

class PiscicultorController extends Controller
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
            $data['users']=User::all();//(5);
            return view('user.index',$data);
        }     
    }

    public function globalReport()
    {
        // 
        if (Auth::user()->rol == 'administrador'){
            $isusers = 1;
            $users=User::where('rol', '=', 'piscicultor')->get();
            $total=[];
            $total['femalecount'] = 0;
            $total['malecount'] = 0;
            $total['activeponds'] = 0;
            $total['inactiveponds'] = 0;
            $total['foodcount'] = 0;
            $total['youngcount'] = 0;
            $total['levantecount'] = 0;
            $total['baitcount'] = 0;
            $total['harvestcount'] = 0;
            $total['sanitarycount'] = 0;
            $total['week4weight'] = 0;
            $total['week12weight'] = 0;
            $total['week24weight'] = 0;
            foreach($users as $user){
                if($user->gender == 'femenino'){
                    $total['femalecount'] = $total['femalecount'] + 1;
                }
                if($user->gender == 'masculino'){
                    $total['malecount'] =  $total['malecount'] + 1;
                }
                $ponds = Pond::where([['user_id', '=', $user->id],['is_enabled', '=', '1']])->get();
                $user->activeponds = 0;
                $user->inactiveponds = 0;
                $user->foodcount = 0;
                $user->youngcount = 0;
                $user->levantecount = 0;
                $user->baitcount = 0;
                $user->harvestcount = 0;
                $user->sanitarycount = 0;
                $user->week4weight = 0;
                $user->week12weight = 0;
                $user->week24weight = 0;
                foreach ($ponds as $pond) {
                    $alevin = Alevin::where('pond_id', '=' , $pond->id)->first();
                    if ($alevin){
                        $user->activeponds = $user->activeponds + 1;
                        $total['activeponds'] = $total['activeponds'] + 1;
                        
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
    
                        $foods = Feeding::where('pond_id', '=' , $pond->id)->get();
                        foreach($foods as $food){
                            $fechaFood = $food->date_of_entry;
                            $fechaFood = Carbon::createFromFormat('Y-m-d', $fechaFood);
                            
                            $weekStartDate = $fechaNueva->startOfWeek()->format('Y-m-d');
                            $weekEndDate = $fechaNueva->endOfWeek()->format('Y-m-d');
                            if($fechaFood->gte($weekStartDate) && $fechaFood->lte($weekEndDate)){
                                $user->foodcount = $user->foodcount + $food->amount;
                                $total['foodcount'] = $total['foodcount'] + $food->amount;
                            }
                        }

                        if ($cantidadDias >= 0 && $cantidadDias <= $youngEnd ){
                            $pond->stage = 'Cria';
                            $user->youngcount = $user->youngcount +1;
                            $total['youngcount'] = $total['youngcount'] +1;
                        }
                        if ($cantidadDias >= $levanteStart && $cantidadDias <= $levanteEnd ){
                            $pond->stage = 'Levante';
                            $user->levantecount = $user->levantecount + 1;
                            $total['levantecount'] = $total['levantecount'] + 1;
                        }
                        if ($cantidadDias >= $baitStart){
                            $pond->stage = 'Cebo';
                            $user->baitcount = $user->baitcount + 1;
                            $total['baitcount'] = $total['baitcount'] + 1;
                        }
                        if ($cantidadDias >= 24){
                            $user->harvestcount = $user->harvestcount + 1;
                            $total['harvestcount'] = $total['harvestcount'] +1;
                        }
                        if ($cantidadDias + 4 >= 24){
                            $user->week4weight = $alevin->amount * 3;
                            $total['week4weight'] = $alevin->amount * 3;
                        }elseif ($cantidadDias + 12 >= 24){
                            $user->week12weight = $alevin->amount * 3;
                            $total['week12weight'] = $alevin->amount * 3;
                        }elseif ($cantidadDias + 24 >= 24){
                            $user->week24weight = $alevin->amount * 3;
                            $total['week24weight'] = $alevin->amount * 3;
                        }
                        
    
                        $sanitaries = Sanitary::where('pond_id', '=' , $pond->id)->get();
                        if(count($sanitaries) > 0){
                            $pond->sanitary = 'Si';
                            $user->sanitarycount = $user->sanitarycount + 1;
                            $total['sanitarycount'] = $total['sanitarycount'] + 1;
                        }else{
                            $pond->sanitary = 'No';
                        }
    
                    }
                    else {
                        $user->inactiveponds = $user->inactiveponds + 1;
                        $total['inactiveponds'] = $total['inactiveponds'] + 1;
                        $pond->stage = 'N/A';
                    }
                }
    
            }
            //$pdf = PDF::loadView('user.userreport', ['users'=>$users, 'ponds'=>$ponds]);
            //return $pdf->stream();
            return view('user.userreport', compact('users', 'ponds', 'isusers', 'total'));
        }
    }

    public function report($id)
    {
        //   
        if (Auth::user()->rol == 'administrador'){
            $isusers = 0;
            $user=User::findOrFail($id);
            //$ponds=Pond::where('user_id', '=', $id)->get();
            $ponds = Pond::where([['user_id', '=', $id],['is_enabled', '=', '1']])->get();
            $user->activeponds = 0;
            $user->inactiveponds = 0;
            $user->foodcount = 0;
            $user->youngcount = 0;
            $user->levantecount = 0;
            $user->baitcount = 0;
            $user->sanitarycount = 0;
            $user->week4weight = 0;
            $user->week12weight = 0;
            $user->week24weight = 0;
            $user->harvestcount = 0;
            foreach ($ponds as $pond) {
                $alevin = Alevin::where('pond_id', '=' , $pond->id)->first();
                if ($alevin){
                    $user->activeponds = $user->activeponds + 1;
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
                    
                    $foods = Feeding::where('pond_id', '=' , $pond->id)->get();
                    foreach($foods as $food){
                        $fechaFood = $food->date_of_entry;
                        $fechaFood = Carbon::createFromFormat('Y-m-d', $fechaFood);
                        
                        $weekStartDate = $fechaNueva->startOfWeek()->format('Y-m-d');
                        $weekEndDate = $fechaNueva->endOfWeek()->format('Y-m-d');
                        if($fechaFood->gte($weekStartDate) && $fechaFood->lte($weekEndDate)){
                            //dd('flag');
                            $user->foodcount = $user->foodcount + $food->amount;
                        }
                    }

                    if ($cantidadDias >= 0 && $cantidadDias <= $youngEnd ){
                        $pond->stage = 'Cria';
                        $user->youngcount = $user->youngcount +1;
                    }
                    if ($cantidadDias >= $levanteStart && $cantidadDias <= $levanteEnd ){
                        $pond->stage = 'Levante';
                        $user->levantecount = $user->levantecount + 1;
                    }
                    if ($cantidadDias >= $baitStart){
                        $pond->stage = 'Cebo';
                        $user->baitcount = $user->baitcount + 1;
                    }
                    if ($cantidadDias >= 24){
                        $user->harvestcount = $user->harvestcount + 1;
                        $user->week24weight = $alevin->amount * 3;
                    }
                    if ($cantidadDias + 4 >= 24){
                        $user->week4weight = $alevin->amount * 3;
                    }elseif ($cantidadDias + 12 >= 24){
                        $user->week12weight = $alevin->amount * 3;
                    }elseif ($cantidadDias + 24 >= 24){
                        $user->week24weight = $alevin->amount * 3;
                    }

                    $sanitaries = Sanitary::where('pond_id', '=' , $pond->id)->get();
                    if(count($sanitaries) > 0){
                        $pond->sanitary = 'Si';
                        $user->sanitarycount = $user->sanitarycount + 1;
                    }else{
                        $pond->sanitary = 'No';
                    }

                }
                else {
                    $user->inactiveponds = $user->inactiveponds + 1;
                    $pond->stage = 'N/A';
                }
            }   
            //dd($user->id);
            return view('user.userreport', compact('user', 'ponds', 'isusers', 'id'));
        }
    }

    public function changePassword($id)
    {
        if (Auth::user()->rol == 'administrador'){
            if(Auth::user()->id == $id){
                $user=User::findOrFail($id);
                $updatepass= 1;
                return view('user.password', compact('user', 'updatepass'));
            }
        } 
        if (Auth::user()->rol == 'piscicultor'){
            if(Auth::user()->id == $id){
                $user=User::findOrFail($id);
                $updatepass= 1;
                return view('user.password', compact('user', 'updatepass'));
            }
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
        return view('user.create');
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
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',//|confirmed',
            'unit_number'=>'required',
            'code' =>'required|string|max:255',
            'gender' =>'required|string|max:255',
            'estate' =>'required|string|max:255',//predio
            'sidewalk' =>'required|string|max:255',//vereda
            'phone'=>'required|Numeric|digits:10',
            'rol' =>'required|string|max:255',
            //'is_enabled' =>'required|boolean',
        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];
        if(request()->rol == 'administrador'){
            $campos['unit_number'] = '';
        }
        $this->validate($request, $campos, $mensaje);

        //$datosPiscicultor=request->all();
        $dataUser= request()->except('_token');
        if($dataUser['rol'] == 'administrador'){
            $dataUser['unit_number'] = 'N/A';
            $dataUser['estate'] = 'N/A';
            $dataUser['sidewalk'] = 'N/A';
        }

        $dataUser['password'] = Hash::make($dataUser['password']);
        $dataUser['email'] = strtolower(request()->email);
        //dd($dataUser['email']);
        User::insert($dataUser);

        //return response()->json($dataUser);
        return redirect('user')->with('mensaje', 'usuario agregado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
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

            return view('user.ponds', compact('user', 'ponds'));
        }   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)//Piscicultor $piscicultor)
    {
        //
        if (Auth::user()->rol == 'administrador'){
            $user=User::findOrFail($id);
            $updatepass= 0;
            return view('user.edit', compact('user', 'updatepass'));
        } 
        if (Auth::user()->rol == 'piscicultor'){
            if(Auth::user()->id == $id){
                $user=User::findOrFail($id);
                $updatepass= 0;
                return view('user.edit', compact('user', 'updatepass'));
            }
        }  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)//Piscicultor $piscicultor)
    {
        //
        if(request()->updatepass == 1){
            $campos=[
                'oldpassword' => 'required|password',
                'password' => 'required',
            ];
    
            $mensaje=[
                //:atribut es un comodin e para el dato
                'required'=>'El :attribute es requerido',
            ];

            $this->validate($request, $campos, $mensaje);
            
            //recolecta datos
            
            if (Hash::check(request()->oldpassword, Auth::user()->password)) {                
                $dataUser['password']= request()->password;//except(['_token','_method']);
                
                $dataUser['password'] = Hash::make($dataUser['password']);
                //buscamos registro con el id que pasamos y actualizamos
                User::where('id', '=', $id)->update( $dataUser);
                $user=User::findOrFail($id);
                
                //return view('user.edit', compact('user'));
                if (Auth::user()->rol == 'administrador'){
                    return redirect('user')->with('mensaje', 'Contraseña modificada');
                } 
                if (Auth::user()->rol == 'piscicultor'){
                    return redirect('home')->with('mensaje', 'Contraseñao modificada');
                }
            } 
        }
        if(request()->updatepass == 0){
            $campos=[
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'unit_number'=>'required',
                'code' =>'required|string|max:255',
                //'gender' =>'required|string|max:255',
                'estate' =>'required|string|max:255',//predio
                'sidewalk' =>'required|string|max:255',//vereda
                'phone'=>'required|Numeric|digits:10',
            ];
    
            $mensaje=[
                //:atribut es un comodin e para el dato
                'required'=>'El :attribute es requerido',
            ];
            if(User::where('id', '=', $id)->firstOrFail()->rol == 'administrador'){
                $campos['unit_number'] = '';
                $campos['estate'] = '';
                $campos['sidewalk'] = '';
            }
            if(User::where('id', '=', $id)->firstOrFail()->email == request()->email){
                $campos['email'] = 'required|string|email|max:255';
            }
            $this->validate($request, $campos, $mensaje);
            
            //recolecta datos
            $dataUser= request()->except(['_token','_method','updatepass']);
            if(User::where('id', '=', $id)->firstOrFail()->rol == 'administrador'){
                $dataUser['unit_number'] = 'N/A';
                $dataUser['estate'] = 'N/A';
                $dataUser['sidewalk'] = 'N/A';
            }
            $dataUser['password'] = User::where('id', '=', $id)->firstOrFail()->password;
            $dataUser['email'] = strtolower(request()->email);
    
            //$dataUser['password'] = Hash::make($dataUser['password']);
            
    
            //buscamos registro con el id que pasamos y actualizamos
            User::where('id', '=', $id)->update( $dataUser);
            $user=User::findOrFail($id);
            //return view('user.edit', compact('user'));
            if (Auth::user()->rol == 'administrador'){
                return redirect('user')->with('mensaje', 'usuario modificado');
            } 
            if (Auth::user()->rol == 'piscicultor'){
                return redirect('home')->with('mensaje', 'usuario modificado');
            } 
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Piscicultor  $piscicultor
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)//Piscicultor $piscicultor)
    {
        //
        $user=User::findOrFail($id);
        User::destroy($id);
        return redirect('user')->with('mensaje', 'usuario borrado');
    }
}
