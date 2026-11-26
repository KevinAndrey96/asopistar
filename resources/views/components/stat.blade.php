@props(['label', 'value', 'hint' => null])
<div {{ $attributes->merge(['class' => 'card p-4']) }}>
    <p class="text-xs font-medium tracking-wide text-slate-500 uppercase">{{ $label }}</p>
    <p class="mt-1 text-2xl font-semibold text-slate-900 tabular-nums">{{ $value }}</p>
    @if ($hint)
        <p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>
    @endif
</div>
