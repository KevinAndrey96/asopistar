<x-layouts.site :title="'Productos · '.config('app.name')" description="Pescado fresco de agua dulce: tilapia, cachama y trucha de los estanques de la asociación, con precios por kilo.">
    <section class="mx-auto max-w-6xl px-4 py-12">
        <h1 class="text-3xl font-semibold">Productos</h1>
        <p class="mt-2 text-slate-600">Pescado de nuestros asociados, entregado con cadena de frío.</p>
        @if ($products->isEmpty())
            <x-empty class="mt-8">Pronto publicaremos el catálogo.</x-empty>
        @else
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    @include('site.partials.product', ['product' => $product])
                @endforeach
            </div>
        @endif
    </section>
</x-layouts.site>
