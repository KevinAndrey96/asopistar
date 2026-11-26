@props(['name', 'label', 'type' => 'text', 'value' => null, 'hint' => null, 'suffix' => null, 'bag' => 'default'])
@php
    $id = $attributes->get('id', 'f-'.$name);
    $error = $errors->getBag($bag)->first($name);
    $current = $bag === 'default' || $errors->getBag($bag)->any() ? old($name, $value) : $value;
    if ($current instanceof \DateTimeInterface) {
        $current = $current->format('Y-m-d');
    }
@endphp
<div {{ $attributes->only('class') }}>
    <label for="{{ $id }}" class="label">{{ $label }}</label>
    <div class="relative">
        @if ($type === 'textarea')
            <textarea id="{{ $id }}" name="{{ $name }}" {{ $attributes->except(['class', 'id'])->merge(['rows' => 4]) }}
                @class(['input', 'border-red-400' => $error]) @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif>{{ $current }}</textarea>
        @elseif ($type === 'select')
            <select id="{{ $id }}" name="{{ $name }}" {{ $attributes->except(['class', 'id']) }}
                @class(['input', 'border-red-400' => $error]) @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif>
                {{ $slot }}
            </select>
        @else
            <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ $current }}" {{ $attributes->except(['class', 'id']) }}
                @class(['input', 'pr-14' => $suffix, 'border-red-400' => $error]) @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif>
            @if ($suffix)
                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs text-slate-500">{{ $suffix }}</span>
            @endif
        @endif
    </div>
    @if ($error)
        <p id="{{ $id }}-error" class="mt-1 text-xs text-red-700">{{ $error }}</p>
    @elseif ($hint)
        <p class="mt-1 text-xs text-slate-500">{{ $hint }}</p>
    @endif
</div>
