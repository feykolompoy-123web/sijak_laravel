@extends('layouts.admin')

@section('judul', $k->nama)

@section('isi')
    <div class="crumb"><a href="{{ route('admin.kegiatan.index') }}">Kegiatan</a> <span>/</span> <b>Detail</b></div>
    <div class="a-head">
        <div><h1>{{ $k->nama }}</h1><p>{{ $k->kode }} · <span class="badge {{ $k->badge }}">{{ $k->status }}</span></p></div>
        <div class="a-actions">
            <a class="btn pri" href="{{ route('admin.kegiatan.edit', $k) }}">Edit</a>
            @if ($k->dipublikasikan)<a class="btn" href="{{ route('kegiatan.detail', $k) }}" target="_blank" rel="noopener">Lihat di situs</a>@endif
            <form method="POST" action="{{ route('admin.kegiatan.destroy', $k) }}" data-konfirmasi="Hapus kegiatan &quot;{{ $k->nama }}&quot; beserta dokumentasinya?">
                @csrf @method('DELETE')<button class="btn red" type="submit">Hapus</button>
            </form>
        </div>
    </div>

    <div class="card">
        <dl class="a-dl">
            <dt>Tanggal</dt><dd>{{ $k->tanggal->translatedFormat('l, d F Y') }}</dd>
            <dt>Waktu</dt><dd>{{ $k->jam }}</dd>
            <dt>Lokasi</dt><dd>{{ $k->lokasi->nama }}</dd>
            <dt>Kategori</dt><dd>{{ $k->kategori->nama }}</dd>
            <dt>Penanggung Jawab</dt><dd>{{ $k->penanggung_jawab }}</dd>
            <dt>Publikasi</dt><dd>{{ $k->dipublikasikan ? 'Ditampilkan di halaman publik' : 'Tidak ditampilkan (hanya admin)' }}</dd>
            <dt>Keterangan</dt><dd>{!! nl2br(e($k->keterangan ?: '-')) !!}</dd>
        </dl>
    </div>

    <div class="a-head">
        <h2>Dokumentasi ({{ $k->dokumentasi->count() }})</h2>
        <div class="a-actions"><a class="btn" href="{{ route('admin.dokumentasi.create', ['kegiatan' => $k->id]) }}">+ Unggah Dokumentasi</a></div>
    </div>
    @if ($k->dokumentasi->isNotEmpty())
        @include('admin.dokumentasi.kartu', ['daftar' => $k->dokumentasi])
    @else
        <div class="card">@include('partials.kosong', ['judul' => 'Belum ada dokumentasi untuk kegiatan ini'])</div>
    @endif
@endsection
