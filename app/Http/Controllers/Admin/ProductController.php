<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Support\ImageStore;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class ProductController extends Controller
{
    public function __construct(private readonly ImageStore $images) {}

    public function index(): View
    {
        return view('admin.products.index', ['products' => Product::query()->orderByDesc('available')->orderBy('name')->get()]);
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => new Product(['available' => true])]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        Product::query()->create([
            ...$request->content(),
            'image_path' => $this->images->replace(null, $request->photo(), false, 'productos'),
        ]);

        return redirect()->route('admin.products.index')->with('status', 'Producto registrado.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', ['product' => $product]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update([
            ...$request->content(),
            'image_path' => $this->images->replace($product->image_path, $request->photo(), $request->boolean('remove_image'), 'productos'),
        ]);

        return redirect()->route('admin.products.index')->with('status', 'Producto actualizado.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->images->delete($product->image_path);
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Producto eliminado.');
    }
}
