<?php

namespace App\Http\Controllers;

use App\Models\Specie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecieController extends Controller
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
            $data['species'] = Specie::all();//(5);
            return view('species.index',$data);
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
        return view('species.create');
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
            'species'=>'required|string|max:100',
            'young_end'=>'required|integer|min:1',
            'levante_start'=>'required|integer',
            'levante_end'=>'required|integer',
            'bait_start'=>'required|integer',

        ];

        $validateDate = request()->except('_token');
        if ($validateDate['levante_start'] != $validateDate['young_end'] + 1){
            $diff = $validateDate['young_end'] + 1;
            $campos=[
                'levante_start'=>'required|integer|min:diff|max:diff',
            ];
        }
        if ($validateDate['levante_end'] <= $validateDate['levante_start']){
            $diff = $validateDate['levante_start'] + 1;
            $campos=[
                'levante_end'=>'required|integer|min:diff',
            ];
        }
        if ($validateDate['bait_start'] != $validateDate['levante_end'] + 1){
            $diff = $validateDate['levante_end'] + 1;
            $campos=[
                'bait_start'=>'required|integer|min:diff|max:diff',
            ];
        }

        $mensaje = [
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
            'young_end.min' => 'Cria Final debe ser mayor que 0',
            'levante_start.min' => 'Levante Inicio debe ser una semana mayor que Cria Final',
            'levante_end.min' => 'Levante Final debe ser mayor que Levante Inicio',
            'bait_start.min' => 'Cebo Inicial debe ser una semana mayor que Levante Final',
        ];

        $this->validate($request, $campos, $mensaje);
        
        $dataSpecie = request()->except('_token');

        Specie::insert($dataSpecie);

        //return response()->json($dataSpecie);
        return redirect('species')->with('mensaje', 'Especie agregada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Specie  $specie
     * @return \Illuminate\Http\Response
     */
    public function show(Specie $specie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Specie  $specie
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $specie = Specie::findOrFail($id);
        
        return view('species.edit', compact('specie'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Specie  $specie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $campos=[
            'species'=>'required|string|max:100',
            'young_end'=>'required|integer|min:1',
            'levante_start'=>'required|integer',
            'levante_end'=>'required|integer',
            'bait_start'=>'required|integer',

        ];

        $validateDate = request()->except('_token');
        if ($validateDate['levante_start'] != $validateDate['young_end'] + 1){
            $diff = $validateDate['young_end'] + 1;
            $campos=[
                'levante_start'=>'required|integer|min:diff|max:diff',
            ];
        }
        if ($validateDate['levante_end'] <= $validateDate['levante_start']){
            $diff = $validateDate['levante_start'] + 1;
            $campos=[
                'levante_end'=>'required|integer|min:diff',
            ];
        }
        if ($validateDate['bait_start'] != $validateDate['levante_end'] + 1){
            $diff = $validateDate['levante_end'] + 1;
            $campos=[
                'bait_start'=>'required|integer|min:diff|max:diff',
            ];
        }

        $mensaje = [
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
            'young_end.min' => 'Cria Final debe ser mayor que 0',
            'levante_start.min' => 'Levante Inicio debe ser una semana mayor que Cria Final',
            'levante_end.min' => 'Levante Final debe ser mayor que Levante Inicio',
            'bait_start.min' => 'Cebo Inicial debe ser una semana mayor que Levante Final',
        ];

        $this->validate($request, $campos, $mensaje);
        
        //recolecta datos
        $dataSpecie= request()->except(['_token','_method']);
        
        //buscamos registro con el id que pasamos y actualizamos
        Specie::where('id', '=', $id)->update( $dataSpecie);
        $specie=Specie::findOrFail($id);
        return redirect('species')->with('mensaje', 'Especie modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Specie  $specie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $specie=Specie::findOrFail($id);
        Specie::destroy($id);
        return redirect('species')->with('mensaje', 'Especie borrada');
    }
}
