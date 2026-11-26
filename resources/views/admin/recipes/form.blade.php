@php $editing = $recipe->exists; @endphp
<x-layouts.app :title="$editing ? 'Editar '.$recipe->title : 'Nueva receta'">
    <x-page-header :title="$editing ? 'Editar '.$recipe->title : 'Nueva receta'" :back="[route('admin.recipes.index'), 'Recetas']" />
    <form method="post" enctype="multipart/form-data" action="{{ $editing ? route('admin.recipes.update', $recipe) : route('admin.recipes.store') }}" class="card max-w-3xl space-y-4 p-6">
        @csrf
        @if ($editing) @method('put') @endif
        <x-field name="title" label="Título" :value="$recipe->title" maxlength="160" required />
        <x-field name="summary" label="Resumen" :value="$recipe->summary" maxlength="300" required />
        <div class="grid gap-4 md:grid-cols-2">
            <x-field name="ingredients" label="Ingredientes" type="textarea" rows="10" :value="$recipe->ingredients" required hint="Uno por línea." />
            <x-field name="steps" label="Preparación" type="textarea" rows="10" :value="$recipe->steps" required hint="Un paso por línea." />
        </div>
        @include('admin.partials.image-field', ['path' => $recipe->image_path])
        <div class="flex gap-2">
            <button class="btn-primary">{{ $editing ? 'Guardar cambios' : 'Publicar receta' }}</button>
            <a href="{{ route('admin.recipes.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</x-layouts.app>
