@php
    $image = \App\Support\ImageStore::url($recipe->image_path);
    $schema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Recipe',
        'name' => $recipe->title,
        'description' => $recipe->summary,
        'image' => $image ? url($image) : null,
        'recipeIngredient' => $recipe->ingredientList(),
        'recipeInstructions' => array_map(fn (string $step) => ['@type' => 'HowToStep', 'text' => $step], $recipe->stepList()),
        'author' => ['@type' => 'Organization', 'name' => config('app.name')],
    ]);
@endphp
<x-layouts.site :title="$recipe->title.' · Recetas · '.config('app.name')" :description="$recipe->summary" :image="$image">
    <x-slot:head>
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
    </x-slot:head>
    <article class="mx-auto max-w-4xl px-4 py-12">
        <a href="{{ route('site.recipes') }}" class="text-sm text-water-700 hover:underline">&larr; Recetas</a>
        <h1 class="mt-2 text-3xl font-semibold">{{ $recipe->title }}</h1>
        <p class="mt-2 text-lg text-slate-600">{{ $recipe->summary }}</p>
        <div class="mt-6 overflow-hidden rounded-xl">
            @include('site.partials.photo', ['path' => $recipe->image_path, 'alt' => $recipe->title, 'class' => 'aspect-[16/9] w-full object-cover'])
        </div>
        <div class="mt-8 grid gap-8 md:grid-cols-3">
            <section>
                <h2 class="text-lg font-semibold">Ingredientes</h2>
                <ul class="mt-3 list-disc space-y-1 pl-5 text-slate-700">
                    @foreach ($recipe->ingredientList() as $ingredient)<li>{{ $ingredient }}</li>@endforeach
                </ul>
            </section>
            <section class="md:col-span-2">
                <h2 class="text-lg font-semibold">Preparación</h2>
                <ol class="mt-3 list-decimal space-y-2 pl-5 text-slate-700">
                    @foreach ($recipe->stepList() as $step)<li>{{ $step }}</li>@endforeach
                </ol>
            </section>
        </div>
    </article>
</x-layouts.site>
