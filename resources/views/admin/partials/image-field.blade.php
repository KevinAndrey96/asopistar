@php $current = \App\Support\ImageStore::url($path); @endphp
<div>
    <label for="f-image" class="label">Foto</label>
    <div class="flex flex-wrap items-start gap-4">
        <img id="image-preview" src="{{ $current }}" alt="" @class(['h-24 w-32 rounded-lg border border-slate-200 object-cover', 'hidden' => ! $current])>
        <div class="space-y-2">
            <input id="f-image" type="file" name="image" accept="image/jpeg,image/png,image/webp" data-preview="image-preview" class="block text-sm">
            <p class="text-xs text-slate-500">JPG, PNG o WebP de hasta 4 MB.</p>
            @if ($current)
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300 text-red-600 focus:ring-red-500"> Quitar la foto actual
                </label>
            @endif
        </div>
    </div>
    @error('image')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
</div>
