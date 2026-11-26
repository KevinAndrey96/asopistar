@php
    $image = \App\Support\ImageStore::url($post->image_path);
    $schema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $post->title,
        'description' => $post->excerpt,
        'datePublished' => $post->published_on->toDateString(),
        'image' => $image ? url($image) : null,
        'publisher' => ['@type' => 'Organization', 'name' => config('app.name')],
    ]);
    $paragraphs = array_filter(array_map(trim(...), preg_split('/\R{2,}/', $post->body) ?: []));
@endphp
<x-layouts.site :title="$post->title.' · '.config('app.name')" :description="$post->excerpt" :image="$image">
    <x-slot:head>
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
    </x-slot:head>
    <article class="mx-auto max-w-3xl px-4 py-12">
        <a href="{{ route('site.posts') }}" class="text-sm text-water-700 hover:underline">&larr; Noticias</a>
        <p class="mt-4 text-sm text-slate-500"><time datetime="{{ $post->published_on->toDateString() }}">{{ $fmt->date($post->published_on) }}</time></p>
        <h1 class="mt-1 text-3xl font-semibold">{{ $post->title }}</h1>
        <p class="mt-2 text-lg text-slate-600">{{ $post->excerpt }}</p>
        @if ($image)
            <img src="{{ $image }}" alt="{{ $post->title }}" class="mt-6 aspect-[16/9] w-full rounded-xl object-cover">
        @endif
        <div class="prose-site mt-8">
            @foreach ($paragraphs as $paragraph)
                <p>{!! nl2br(e($paragraph)) !!}</p>
            @endforeach
        </div>
    </article>
</x-layouts.site>
