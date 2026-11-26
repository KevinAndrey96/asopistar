@if ($rows->isEmpty())
    <x-empty class="py-6">{{ $empty }}</x-empty>
@else
    <div class="card overflow-x-auto">
        <table class="table-base table-stack">
            <thead>
                <tr>
                    @foreach ($headers as $header)<th>{{ $header }}</th>@endforeach
                    @if ($canLog)<th><span class="sr-only">Acciones</span></th>@endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($rows as $row)
                    <tr>
                        @foreach ($cells($row) as $i => $cell)
                            <td data-label="{{ $headers[$i] }}">{{ $cell }}</td>
                        @endforeach
                        @if ($canLog)
                            <td class="text-right"><x-delete-button :action="$destroy($row)" confirm="¿Eliminar este registro?" /></td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
