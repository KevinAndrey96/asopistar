<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request as RequestAlias;

class ProductController extends Controller
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
            $data['products'] = Product::all();//(5);
            return view('product.index',$data);
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
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $campos=[
            'name'=>'required|string',
            'description'=>'required|string',
            'price'=>'required|string',
            //'image_url'=>'required|url',
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

        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->image_url = 'default.png';
        $product->save();
        if ($request->hasFile('image')) {
            $pathName = Sprintf('products/%s.png', $product->id);
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
                                ->getPathPrefix() . 'products/' . $product->id . '.png', 'r'),
                    ],
                    [
                        'name' => 'path',
                        'contents' => 'products'
                    ]
                ]
            ]);
            $product->image_url = '/storage/products/' . $product->id . '.png';
            $product->save();
            unlink(storage_path('app/public/products/'.$product->id.'.png'));
        }

        return redirect('product')->with('mensaje', 'Producto agregado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $product = Product::findOrFail($id);

        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $campos=[
            'name'=>'required|string',
            'description'=>'required|string',
            'price'=>'required|string',
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
        $product=Product::findOrFail($id);
        if ($request->hasFile('image')) {
            $pathName = Sprintf('products/%s.png', $product->id);
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
                                ->getPathPrefix() . 'products/' . $product->id . '.png', 'r'),
                    ],
                    [
                        'name' => 'path',
                        'contents' => 'products'
                    ]
                ]
            ]);
            $product->image_url = '/storage/products/' . $product->id . '.png';
            $product->save();
            unlink(storage_path('app/public/products/'.$product->id.'.png'));
        }

        //buscamos registro con el id que pasamos y actualizamos
        Product::where('id', '=', $id)->update( $dataproduct);

        return redirect('product')->with('mensaje', 'Producto modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $product=Product::findOrFail($id);
        Product::destroy($id);
        return redirect('product')->with('mensaje', 'Producto borrado');
    }
}
