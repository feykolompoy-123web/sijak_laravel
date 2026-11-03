{{-- Galeri dokumentasi. Parameter: $daftar (koleksi Dokumentasi), $solo (opsional) --}}
<div class="gal{{ ($solo ?? false) ? ' solo' : '' }}">
    @foreach ($daftar as $d)
        <figure class="gl">
            <a class="im" href="{{ $d->url }}" target="_blank" rel="noopener">
                @if ($d->is_foto)
                    <img src="{{ $d->url }}" alt="{{ $d->judul }}" loading="lazy">
                @else
                    <span class="dok-ico">{{ strtoupper(pathinfo($d->path, PATHINFO_EXTENSION)) }}</span>
                @endif
            </a>
            <figcaption>
                <b>{{ $d->judul }}</b>
                @if ($d->kegiatan)
                    <span>{{ $d->kegiatan->tanggal->translatedFormat('d M Y') }}@if ($d->kegiatan->dipublikasikan) · <a href="{{ route('kegiatan.detail', $d->kegiatan) }}">{{ $d->kegiatan->nama }}</a>@endif</span>
                @endif
            </figcaption>
        </figure>
    @endforeach
</div>
