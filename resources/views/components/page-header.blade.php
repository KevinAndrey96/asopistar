@props(['title', 'subtitle' => null, 'back' => null])
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div>
        @if ($back)
            <a href="{{ $back[0] }}" class="text-sm text-water-700 hover:underline">&larr; {{ $back[1] }}</a>
        @endif
        <h1 class="text-2xl font-semibold text-slate-900">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-1 text-sm text-slate-600">{{ $subtitle }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="flex flex-wrap gap-2">{{ $actions }}</div>
    @endisset
</div>
