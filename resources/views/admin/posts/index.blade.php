<x-layouts.app title="Noticias">
    <x-page-header title="Noticias" subtitle="Publicaciones del sitio público. Las de fecha futura quedan programadas.">
        <x-slot:actions><a href="{{ route('admin.posts.create') }}" class="btn-primary">Nueva noticia</a></x-slot:actions>
    </x-page-header>
    @if ($posts->isEmpty())
        <x-empty>Aún no hay noticias.</x-empty>
    @else
        <div class="card overflow-x-auto">
            <table class="table-base table-stack">
                <thead><tr><th>Título</th><th>Publicación</th><th></th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($posts as $post)
                        <tr>
                            <td data-label="Título">{{ $post->title }}</td>
                            <td data-label="Publicación">{{ $fmt->date($post->published_on) }}{{ $post->published_on->isFuture() ? ' (programada)' : '' }}</td>
                            <td class="space-x-3 text-right whitespace-nowrap">
                                @unless ($post->published_on->isFuture())
                                    <a href="{{ route('site.post', $post) }}" class="link">Ver</a>
                                @endunless
                                <a href="{{ route('admin.posts.edit', $post) }}" class="link">Editar</a>
                                <x-delete-button :action="route('admin.posts.destroy', $post)" confirm="¿Eliminar esta noticia?" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $posts->links() }}
    @endif
</x-layouts.app>
