<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request as RequestAlias;

class BlogController extends Controller
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
            $data['blogs'] = Blog::all();//(5);
            return view('blog.index',$data);
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
        return view('blog.create');
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
            'title'=>'required|string',
            'date'=>'required|date',
            'description'=>'required|string',
            //'image_url'=>'required|url',
            'content'=>'required|string',
            'image'=>'required|max:10000|mimes:jpeg,png,jpg',
        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
            'image.required'=>'La imagen es requerida'
        ];
        //dd(request()->image_url);
        $this->validate($request, $campos, $mensaje);

        $datablog= request()->except(['_token','_method']);

        /*if($request->hasFile('image')){
            //capturar foto/archivo
            $datablog['image_url']=$request->file('image')->store('uploads/blogs','public');
        }*/
        
        $blog = new Blog();
        $blog->title = $request->input('title');
        $blog->date = $request->input('date');
        $blog->description = $request->input('description');
        $blog->content = $request->input('content');
        $blog->image_url = 'default.png';
        $blog->save();
        if ($request->hasFile('image')) {
            $pathName = Sprintf('blogs/%s.png', $blog->id);
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
                                ->getPathPrefix() . 'blogs/' . $blog->id . '.png', 'r'),
                    ],
                    [
                        'name' => 'path',
                        'contents' => 'blogs'
                    ]
                ]
            ]);
            $blog->image_url = '/storage/blogs/' . $blog->id . '.png';
            $blog->save();
            unlink(storage_path('app/public/blogs/'.$blog->id.'.png'));
        }

        //Blog::insert($datablog);

        return redirect('blog')->with('mensaje', 'Blog agregado con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $blog = Blog::findOrFail($id);
        return view('blog.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $campos=[
            'title'=>'required|string',
            'date'=>'required|date',
            'description'=>'required|string',
            //'image_url'=>'required|string',
            'content'=>'required|string',
            //'image'=>'required|max:10000|mimes:jpeg,png,jpg',


        ];
        $mensaje=[
            //:atribut es un comodin e para el dato
            'required'=>'El :attribute es requerido',
            //'image.required'=>'La imagen es requerida'
        ];

        $this->validate($request, $campos, $mensaje);

        $datablog= request()->except(['_token','_method', 'image']);
        $blog=Blog::findOrFail($id);
        if ($request->hasFile('image')) {
            $pathName = Sprintf('blogs/%s.png', $blog->id);
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
                                ->getPathPrefix() . 'blogs/' . $blog->id . '.png', 'r'),
                    ],
                    [
                        'name' => 'path',
                        'contents' => 'blogs'
                    ]
                ]
            ]);
            $blog->image_url = '/storage/blogs/' . $blog->id . '.png';
            $blog->save();
            unlink(storage_path('app/public/blogs/'.$blog->id.'.png'));
        }

        //buscamos registro con el id que pasamos y actualizamos
        blog::where('id', '=', $id)->update( $datablog );
        
        return redirect('blog')->with('mensaje', 'Blog modificado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $blog=Blog::findOrFail($id);
        Blog::destroy($id);
        return redirect('blog')->with('mensaje', 'blog borrado');
    }
}
