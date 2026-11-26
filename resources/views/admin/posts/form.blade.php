@php $editing = $post->exists; @endphp
<x-layouts.app :title="$editing ? 'Editar noticia' : 'Nueva noticia'">
    <x-page-header :title="$editing ? 'Editar noticia' : 'Nueva noticia'" :back="[route('admin.posts.index'), 'Noticias']" />
    <form method="post" enctype="multipart/form-data" action="{{ $editing ? route('admin.posts.update', $post) : route('admin.posts.store') }}" class="card max-w-3xl space-y-4 p-6">
        @csrf
        @if ($editing) @method('put') @endif
        <x-field name="title" label="Título" :value="$post->title" maxlength="160" required />
        <x-field name="excerpt" label="Resumen" :value="$post->excerpt" maxlength="300" required hint="Aparece en el listado y en buscadores." />
        <x-field name="body" label="Contenido" type="textarea" rows="12" :value="$post->body" required hint="Separa los párrafos con una línea en blanco." />
        <x-field name="published_on" label="Fecha de publicación" type="date" :value="$post->published_on" class="sm:w-1/3" required />
        @include('admin.partials.image-field', ['path' => $post->image_path])
        <div class="flex gap-2">
            <button class="btn-primary">{{ $editing ? 'Guardar cambios' : 'Publicar' }}</button>
            <a href="{{ route('admin.posts.index') }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</x-layouts.app>
