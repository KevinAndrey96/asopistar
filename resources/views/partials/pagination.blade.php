@if ($paginator->hasPages())
    <nav class="mt-4 flex items-center justify-between text-sm" aria-label="Paginación">
        @if ($paginator->onFirstPage())
            <span class="text-slate-400">&larr; Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="link">&larr; Anterior</a>
        @endif
        <span class="text-slate-500">Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}</span>
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="link">Siguiente &rarr;</a>
        @else
            <span class="text-slate-400">Siguiente &rarr;</span>
        @endif
    </nav>
@endif
