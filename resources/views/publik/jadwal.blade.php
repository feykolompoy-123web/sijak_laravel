@extends('layouts.publik')

@section('judul', 'Jadwal Kegiatan')
@section('halaman', 'pjadwal')

@section('isi')
    <div class="sh"><h1>Jadwal Kegiatan</h1><p>Daftar kegiatan Command Center Kota Tomohon yang dipublikasikan.</p></div>

    <form class="card fl np" method="GET" action="{{ route('jadwal') }}">
        <input name="q" value="{{ request('q') }}" placeholder="Cari nama kegiatan" aria-label="Cari nama kegiatan">
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" aria-label="Tanggal">
        <select name="kategori" aria-label="Kategori">
            <option value="">Semua Kategori</option>
            @foreach ($kategori as $c)<option value="{{ $c->id }}" @selected(request('kategori') == $c->id)>{{ $c->nama }}</option>@endforeach
        </select>
        <select name="waktu" aria-label="Waktu">
            <option value="mendatang" @selected(request('waktu', 'mendatang') === 'mendatang')>Mendatang</option>
            <option value="lalu" @selected(request('waktu') === 'lalu')>Sudah lewat</option>
            <option value="semua" @selected(request('waktu') === 'semua')>Semua</option>
        </select>
        <button class="btn" type="submit">Terapkan</button>
        <a class="btn" href="{{ route('jadwal') }}">Reset</a>
    </form>

    @if ($daftar->isNotEmpty())
        <ul class="agl">@foreach ($daftar as $k)@include('partials.agenda', ['k' => $k])@endforeach</ul>
        {{ $daftar->links() }}
    @else
        @include('partials.kosong', ['judul' => 'Tidak ada agenda ditemukan', 'ket' => 'Ubah kata kunci atau filter pencarian.'])
    @endif
@endsection
