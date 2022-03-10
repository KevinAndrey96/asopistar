<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request as RequestAlias;

class RecipesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function index()
    {
        if (Auth::user()->rol === 'administrador'){
            $data['recipes'] = Recipe::all();
            return view('recipes.index',$data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('recipes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $campos=[
            'title'=>'required|string',
            'description'=>'required|string',
            'ingredients'=>'required|string',
            'image'=>'required|max:10000|mimes:jpeg,png,jpg',
        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
            'image.required'=>'La imagen es requerida'
        ];
        //dd(request()->image_url);
        $this->validate($request, $campos, $mensaje);

        $dataproduct= request()->except(['_token','_method', 'image']);

        $recipe = new Recipe();
        $recipe->title = $request->input('title');
        $recipe->description = $request->input('description');
        $recipe->ingredients = $request->input('ingredients');
        $recipe->image_url = 'default.png';
        $recipe->save();
        if ($request->hasFile('image')) {
            $pathName = Sprintf('recipes/%s.png', $recipe->id);
            Storage::disk('public')->put($pathName, file_get_contents($request->file('image')));
            $client = new Client();
            $url = "https://portal.asopistar.com/upload.php";
            $client->request(RequestAlias::METHOD_POST, $url, [
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen(
                            Storage::disk('public')
                                ->getDriver()
                                ->getAdapter()
                                ->getPathPrefix() . 'recipes/' . $recipe->id . '.png', 'r'),
                    ],
                    [
                        'name' => 'path',
                        'contents' => 'recipes'
                    ]
                ]
            ]);
            $recipe->image_url = '/storage/recipes/' . $recipe->id . '.png';
            $recipe->save();
            unlink(storage_path('app/public/recipes/'.$recipe->id.'.png'));
        }

        return redirect('recipes')->with('mensaje', 'Receta agregado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $recipe = Recipe::findOrFail($id);

        return view('recipes.edit', compact('recipe'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $campos = [
            'title'=>'required|string',
            'description'=>'required|string',
            'ingredients'=>'required|string',
            //'image_url'=>'required|url',
            //'image'=>'required|max:10000|mimes:jpeg,png,jpg',
        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
            //'image.required'=>'La imagen es requerida'
        ];
        //dd(request()->image_url);
        $this->validate($request, $campos, $mensaje);
        $dataproduct= request()->except(['_token','_method', 'image']);
        $recipe = Recipe::findOrFail($id);
        if ($request->hasFile('image')) {
            $pathName = Sprintf('recipes/%s.png', $recipe->id);
            Storage::disk('public')->put($pathName, file_get_contents($request->file('image')));
            $client = new Client();
            $url = "https://portal.asopistar.com/upload.php";
            $client->request(RequestAlias::METHOD_POST, $url, [
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen(
                            Storage::disk('public')
                                ->getDriver()
                                ->getAdapter()
                                ->getPathPrefix() . 'recipes/' . $recipe->id . '.png', 'r'),
                    ],
                    [
                        'name' => 'path',
                        'contents' => 'recipes'
                    ]
                ]
            ]);
            $recipe->image_url = '/storage/recipes/' . $recipe->id . '.png';
            $recipe->save();
            unlink(storage_path('app/public/recipes/'.$recipe->id.'.png'));
        }

        //buscamos registro con el id que pasamos y actualizamos
        Recipe::where('id', '=', $id)->update( $dataproduct);

        return redirect('recipes')->with('mensaje', 'Receta modificada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Recipe::destroy($id);
        return redirect('recipes')->with('mensaje', 'Receta borrada');
    }
}
