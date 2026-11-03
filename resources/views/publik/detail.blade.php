@extends('layouts.publik')

@section('judul', $k->nama)
@section('halaman', 'pdetail')

@section('isi')
    <div class="crumb"><a href="{{ route('jadwal') }}">Jadwal Kegiatan</a> <span>/</span> <b>Detail</b></div>

    <div class="card">
        <div class="dhd">
            <h1>{{ $k->nama }}</h1>
            <p><span class="badge {{ $k->badge }}">{{ $k->status }}</span> &nbsp; <span class="mu">{{ $k->kode }}</span></p>
        </div>

        <div class="facts">
            <div class="fc"><small>Tanggal</small><br><b>{{ $k->tanggal->translatedFormat('l, d F Y') }}</b></div>
            <div class="fc"><small>Waktu</small><br><b>{{ $k->jam }}</b></div>
            <div class="fc"><small>Lokasi</small><br><b>{{ $k->lokasi->nama }}</b></div>
            <div class="fc"><small>Kategori</small><br><b>{{ $k->kategori->nama }}</b></div>
            <div class="fc"><small>Penanggung Jawab</small><br><b>{{ $k->penanggung_jawab }}</b></div>
        </div>

        <h3>Keterangan</h3>
        <p>{!! nl2br(e($k->keterangan ?: 'Tidak ada keterangan tambahan.')) !!}</p>
    </div>

    @if ($foto->isNotEmpty())
        <section class="blk2">
            <h2>Dokumentasi Kegiatan</h2>
            @include('partials.galeri', ['daftar' => $foto, 'solo' => $foto->count() === 1])
        </section>
    @endif

    <p><a class="btn" href="{{ route('jadwal') }}">← Kembali ke Jadwal</a></p>
@endsection
