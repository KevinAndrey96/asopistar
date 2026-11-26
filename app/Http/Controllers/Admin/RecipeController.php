<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RecipeRequest;
use App\Models\Recipe;
use App\Support\ImageStore;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class RecipeController extends Controller
{
    public function __construct(private readonly ImageStore $images) {}

    public function index(): View
    {
        return view('admin.recipes.index', ['recipes' => Recipe::query()->orderBy('title')->get()]);
    }

    public function create(): View
    {
        return view('admin.recipes.form', ['recipe' => new Recipe]);
    }

    public function store(RecipeRequest $request): RedirectResponse
    {
        Recipe::query()->create([
            ...$request->content(),
            'image_path' => $this->images->replace(null, $request->photo(), false, 'recetas'),
        ]);

        return redirect()->route('admin.recipes.index')->with('status', 'Receta publicada.');
    }

    public function edit(Recipe $recipe): View
    {
        return view('admin.recipes.form', ['recipe' => $recipe]);
    }

    public function update(RecipeRequest $request, Recipe $recipe): RedirectResponse
    {
        $recipe->update([
            ...$request->content(),
            'image_path' => $this->images->replace($recipe->image_path, $request->photo(), $request->boolean('remove_image'), 'recetas'),
        ]);

        return redirect()->route('admin.recipes.index')->with('status', 'Receta actualizada.');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        $this->images->delete($recipe->image_path);
        $recipe->delete();

        return redirect()->route('admin.recipes.index')->with('status', 'Receta eliminada.');
    }
}
