<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use App\Support\ImageStore;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class PostController extends Controller
{
    public function __construct(private readonly ImageStore $images) {}

    public function index(): View
    {
        return view('admin.posts.index', ['posts' => Post::query()->latest('published_on')->latest('id')->paginate(20)]);
    }

    public function create(): View
    {
        return view('admin.posts.form', ['post' => new Post(['published_on' => CarbonImmutable::today()])]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        Post::query()->create([
            ...$request->content(),
            'image_path' => $this->images->replace(null, $request->photo(), false, 'noticias'),
        ]);

        return redirect()->route('admin.posts.index')->with('status', 'Noticia publicada.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', ['post' => $post]);
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $post->update([
            ...$request->content(),
            'image_path' => $this->images->replace($post->image_path, $request->photo(), $request->boolean('remove_image'), 'noticias'),
        ]);

        return redirect()->route('admin.posts.index')->with('status', 'Noticia actualizada.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->images->delete($post->image_path);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'Noticia eliminada.');
    }
}
