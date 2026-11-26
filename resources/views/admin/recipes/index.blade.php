<x-layouts.app title="Recetas">
    <x-page-header title="Recetas" subtitle="Preparaciones con pescado de la asociación para el sitio público.">
        <x-slot:actions><a href="{{ route('admin.recipes.create') }}" class="btn-primary">Nueva receta</a></x-slot:actions>
    </x-page-header>
    @if ($recipes->isEmpty())
        <x-empty>Aún no hay recetas.</x-empty>
    @else
        <div class="card overflow-x-auto">
            <table class="table-base table-stack">
                <thead><tr><th>Receta</th><th>Resumen</th><th></th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($recipes as $recipe)
                        <tr>
                            <td data-label="Receta">{{ $recipe->title }}</td>
                            <td data-label="Resumen" class="text-slate-500">{{ \Illuminate\Support\Str::limit($recipe->summary, 90) }}</td>
                            <td class="space-x-3 text-right whitespace-nowrap">
                                <a href="{{ route('site.recipe', $recipe) }}" class="link">Ver</a>
                                <a href="{{ route('admin.recipes.edit', $recipe) }}" class="link">Editar</a>
                                <x-delete-button :action="route('admin.recipes.destroy', $recipe)" :confirm="'¿Eliminar '.$recipe->title.'?'" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.app>
