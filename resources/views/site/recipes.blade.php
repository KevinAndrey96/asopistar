<x-layouts.site :title="'Recetas con pescado · '.config('app.name')" description="Recetas con tilapia, cachama y trucha: preparaciones sencillas con pescado fresco de agua dulce.">
    <section class="mx-auto max-w-6xl px-4 py-12">
        <h1 class="text-3xl font-semibold">Recetas</h1>
        @if ($recipes->isEmpty())
            <x-empty class="mt-8">Pronto publicaremos recetas.</x-empty>
        @else
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($recipes as $recipe)
                    <a href="{{ route('site.recipe', $recipe) }}" class="card group overflow-hidden">
                        @include('site.partials.photo', ['path' => $recipe->image_path, 'alt' => $recipe->title])
                        <div class="p-4">
                            <h2 class="font-semibold group-hover:text-water-700">{{ $recipe->title }}</h2>
                            <p class="mt-1 text-sm text-slate-600">{{ $recipe->summary }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</x-layouts.site>
