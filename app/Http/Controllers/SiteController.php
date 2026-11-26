<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

/**
 * Public site of the association: products, recipes and news.
 */
final class SiteController extends Controller
{
    public function home(): View
    {
        return view('site.home', [
            'products' => Product::query()->where('available', true)->orderBy('name')->limit(4)->get(),
            'recipes' => Recipe::query()->latest('id')->limit(3)->get(),
            'posts' => Post::query()->published()->limit(3)->get(),
        ]);
    }

    public function products(): View
    {
        return view('site.products', ['products' => Product::query()->where('available', true)->orderBy('name')->get()]);
    }

    public function recipes(): View
    {
        return view('site.recipes', ['recipes' => Recipe::query()->orderBy('title')->get()]);
    }

    public function recipe(Recipe $recipe): View
    {
        return view('site.recipe', ['recipe' => $recipe]);
    }

    public function posts(): View
    {
        return view('site.posts', ['posts' => Post::query()->published()->paginate(9)]);
    }

    public function post(Post $post): View
    {
        abort_if($post->published_on->isFuture(), 404);

        return view('site.post', ['post' => $post]);
    }

    public function sitemap(): Response
    {
        $urls = [
            route('home'), route('site.products'), route('site.recipes'), route('site.posts'),
            ...Recipe::query()->orderBy('id')->get()->map(fn (Recipe $recipe) => route('site.recipe', $recipe)),
            ...Post::query()->published()->get()->map(fn (Post $post) => route('site.post', $post)),
        ];

        return response()->view('site.sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $private = ['/panel', '/admin', '/estanques', '/ciclos', '/ingresar', '/cuenta'];
        $lines = ['User-agent: *', ...array_map(fn (string $path) => 'Disallow: '.$path, $private), 'Sitemap: '.route('sitemap')];

        return response(implode("\n", $lines)."\n")->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
