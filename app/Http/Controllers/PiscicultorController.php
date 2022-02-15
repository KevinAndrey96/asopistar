<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Pond;
use App\Models\Alevin;
use App\Models\Specie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',//|confirmed',
            'code' =>'required|string|max:255',
            'estate' =>'required|string|max:255',//predio
            'sidewalk' =>'required|string|max:255',//vereda
            'rol' =>'required|string|max:255',
            //'is_enabled' =>'required|boolean',
        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);

        //$datosPiscicultor=request->all();
        $dataUser= request()->except('_token');

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
        return view('user.edit', compact('user'));
        } 
        if (Auth::user()->rol == 'piscicultor'){
            if(Auth::user()->id == $id){
                $user=User::findOrFail($id);
                return view('user.edit', compact('user'));
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
        $campos=[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            //'password' => 'required|string|min:8',//|confirmed',
            'code' =>'required|string|max:255',
            'estate' =>'required|string|max:255',//predio
            'sidewalk' =>'required|string|max:255',//vereda
            //'is_enabled' =>'required|boolean',
        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
        ];

        $this->validate($request, $campos, $mensaje);
        
        //recolecta datos
        $dataUser= request()->except(['_token','_method']);

        $dataUser['password'] = User::where('id', '=', $id)->firstOrFail()->password;

        //$dataUser['password'] = Hash::make($dataUser['password']);
        

        //buscamos registro con el id que pasamos y actualizamos
        User::where('id', '=', $id)->update( $dataUser);
        $user=User::findOrFail($id);
        //return view('user.edit', compact('user'));
        if (Auth::user()->rol == 'administrador'){
            return redirect('user')->with('mensaje', 'usuario modificado');
        } 
        if (Auth::user()->rol == 'piscicultor'){
            return redirect('home');
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
