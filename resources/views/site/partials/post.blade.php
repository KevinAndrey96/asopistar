<article class="card overflow-hidden">
    <a href="{{ route('site.post', $post) }}" class="group block">
        @include('site.partials.photo', ['path' => $post->image_path, 'alt' => $post->title])
        <div class="p-4">
            <p class="text-xs text-slate-500"><time datetime="{{ $post->published_on->toDateString() }}">{{ $fmt->date($post->published_on) }}</time></p>
            <h3 class="mt-1 font-semibold group-hover:text-water-700">{{ $post->title }}</h3>
            <p class="mt-1 text-sm text-slate-600">{{ $post->excerpt }}</p>
        </div>
    </a>
</article>
