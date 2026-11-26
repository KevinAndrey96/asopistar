<x-layouts.site :title="'Noticias · '.config('app.name')" description="Noticias de la asociación de piscicultores: cosechas, capacitaciones y vida en el campo.">
    <section class="mx-auto max-w-6xl px-4 py-12">
        <h1 class="text-3xl font-semibold">Noticias</h1>
        @if ($posts->isEmpty())
            <x-empty class="mt-8">Aún no hay noticias publicadas.</x-empty>
        @else
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    @include('site.partials.post', ['post' => $post])
                @endforeach
            </div>
            {{ $posts->links() }}
        @endif
    </section>
</x-layouts.site>
