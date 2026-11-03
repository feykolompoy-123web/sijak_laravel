{{-- Kartu dokumentasi di area admin. Parameter: $daftar --}}
<div class="a-docs">
    @foreach ($daftar as $d)
        <div class="a-doc">
            <a class="th" href="{{ $d->url }}" target="_blank" rel="noopener">
                @if ($d->is_foto)<img src="{{ $d->url }}" alt="{{ $d->judul }}" loading="lazy">@else<span class="dok-ico">{{ strtoupper(pathinfo($d->path, PATHINFO_EXTENSION)) }}</span>@endif
            </a>
            <div class="tb">
                <b>{{ $d->judul }}</b>
                <small>{{ $d->kegiatan ? $d->kegiatan->kode.' · '.$d->kegiatan->nama : 'Tidak terkait kegiatan' }}</small>
                <small>{{ $d->tipe }} · {{ $d->ukuran_teks }}</small>
                <span><span class="badge {{ $d->dipublikasikan ? 'a-badge-ya' : 'a-badge-tdk' }}">{{ $d->dipublikasikan ? 'Publik' : 'Disembunyikan' }}</span></span>
                <div class="a-actions">
                    <a class="btn sm" href="{{ route('admin.dokumentasi.edit', $d) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.dokumentasi.destroy', $d) }}" data-konfirmasi="Hapus dokumentasi &quot;{{ $d->judul }}&quot;? Berkasnya ikut terhapus.">
                        @csrf @method('DELETE')<button class="btn sm red" type="submit">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
