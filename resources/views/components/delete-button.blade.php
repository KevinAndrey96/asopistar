@props(['action', 'confirm', 'label' => 'Eliminar'])
<form method="post" action="{{ $action }}" data-confirm="{{ $confirm }}" class="inline">
    @csrf
    @method('delete')
    <button {{ $attributes->merge(['class' => 'text-xs font-medium text-red-700 hover:underline']) }}>{{ $label }}</button>
</form>
