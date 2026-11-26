<article class="card overflow-hidden">
    @include('site.partials.photo', ['path' => $product->image_path, 'alt' => $product->name])
    <div class="p-4">
        <h3 class="font-semibold">{{ $product->name }}</h3>
        <p class="mt-1 text-sm text-slate-600">{{ $product->description }}</p>
        <p class="mt-3 text-lg font-semibold text-water-800">{{ $fmt->cop($product->price_cop) }} <span class="text-sm font-normal text-slate-500">/ {{ $product->unit }}</span></p>
    </div>
</article>
