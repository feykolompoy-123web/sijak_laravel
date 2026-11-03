@if ($paginator->hasPages())
    <nav class="pager" role="navigation" aria-label="Navigasi halaman">
        <span>Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data</span>
        <div class="pg-acts">
            @if ($paginator->onFirstPage())
                <span class="btn sm" aria-disabled="true" style="opacity:.45">‹ Sebelumnya</span>
            @else
                <a class="btn sm" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹ Sebelumnya</a>
            @endif
            <span>Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>
            @if ($paginator->hasMorePages())
                <a class="btn sm" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya ›</a>
            @else
                <span class="btn sm" aria-disabled="true" style="opacity:.45">Berikutnya ›</span>
            @endif
        </div>
    </nav>
@endif
