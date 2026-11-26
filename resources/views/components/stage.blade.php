@props(['stage'])
@php
    $tone = match ($stage) {
        \App\Domain\Aquaculture\GrowthStage::Fry => 'bg-sky-100 text-sky-800',
        \App\Domain\Aquaculture\GrowthStage::GrowOut => 'bg-water-100 text-water-800',
        \App\Domain\Aquaculture\GrowthStage::Fattening => 'bg-amber-100 text-amber-800',
    };
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex rounded-full px-2 py-0.5 text-xs font-semibold '.$tone]) }}>{{ $stage->label() }}</span>
