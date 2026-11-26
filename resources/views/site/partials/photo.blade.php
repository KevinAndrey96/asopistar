@php $url = \App\Support\ImageStore::url($path); @endphp
@if ($url)
    <img src="{{ $url }}" alt="{{ $alt }}" loading="lazy" class="{{ $class ?? 'aspect-[4/3] w-full object-cover' }}">
@else
    <div class="{{ $class ?? 'aspect-[4/3] w-full' }} flex items-center justify-center bg-gradient-to-br from-water-100 to-water-200" role="img" aria-label="{{ $alt }}">
        <x-logo class="size-14 opacity-70" />
    </div>
@endif
