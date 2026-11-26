<x-layouts.site :title="config('app.name').' · Pescado fresco de nuestros estanques'">
    <section class="bg-gradient-to-br from-water-800 to-water-600 text-white">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 py-16 md:grid-cols-2 md:items-center">
            <div>
                <p class="text-sm font-semibold tracking-wide text-water-100 uppercase">Asociación de piscicultores</p>
                <h1 class="mt-2 text-4xl font-bold leading-tight">Pescado fresco, criado con trazabilidad desde la siembra</h1>
                <p class="mt-4 text-lg text-water-50">Tilapia, cachama y trucha de familias piscicultoras de la región. Cada lote lleva su bitácora de alimentación, sanidad y cosecha.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('site.products') }}" class="btn bg-white text-water-800 hover:bg-water-50">Ver productos</a>
                    <a href="{{ route('site.recipes') }}" class="btn border border-white/40 text-white hover:bg-white/10">Recetas</a>
                </div>
            </div>
            <ul class="grid grid-cols-2 gap-4 text-sm">
                <li class="rounded-xl bg-white/10 p-4"><strong class="block text-base">Siembra</strong>Alevinos de proveedores registrados.</li>
                <li class="rounded-xl bg-white/10 p-4"><strong class="block text-base">Alimentación</strong>Concentrado y proteína por etapa.</li>
                <li class="rounded-xl bg-white/10 p-4"><strong class="block text-base">Sanidad</strong>Tratamientos anotados en bitácora.</li>
                <li class="rounded-xl bg-white/10 p-4"><strong class="block text-base">Cadena de frío</strong>Hielo desde el sacrificio hasta la entrega.</li>
            </ul>
        </div>
    </section>

    @if ($products->isNotEmpty())
        <section class="mx-auto max-w-6xl px-4 py-12">
            <div class="mb-6 flex items-end justify-between">
                <h2 class="text-2xl font-semibold">Productos</h2>
                <a href="{{ route('site.products') }}" class="link text-sm">Ver todos</a>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    @include('site.partials.product', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif

    @if ($recipes->isNotEmpty())
        <section class="bg-slate-50">
            <div class="mx-auto max-w-6xl px-4 py-12">
                <h2 class="mb-6 text-2xl font-semibold">Recetas</h2>
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($recipes as $recipe)
                        <a href="{{ route('site.recipe', $recipe) }}" class="card group overflow-hidden">
                            @include('site.partials.photo', ['path' => $recipe->image_path, 'alt' => $recipe->title])
                            <div class="p-4">
                                <h3 class="font-semibold group-hover:text-water-700">{{ $recipe->title }}</h3>
                                <p class="mt-1 text-sm text-slate-600">{{ $recipe->summary }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($posts->isNotEmpty())
        <section class="mx-auto max-w-6xl px-4 py-12">
            <h2 class="mb-6 text-2xl font-semibold">Noticias</h2>
            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($posts as $post)
                    @include('site.partials.post', ['post' => $post])
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.site>
