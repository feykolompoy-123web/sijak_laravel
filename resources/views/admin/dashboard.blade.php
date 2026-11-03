@extends('layouts.admin')

@section('judul', 'Dashboard')

@section('isi')
    <div class="a-head">
        <div>
            <h1>Dashboard</h1>
            <p>{{ now()->translatedFormat('l, d F Y') }} · Selamat datang, {{ auth()->user()->name }}.</p>
        </div>
        <div class="a-actions">
            <a class="btn pri" href="{{ route('admin.kegiatan.create') }}">+ Tambah Kegiatan</a>
            <a class="btn" href="{{ route('admin.dokumentasi.create') }}">Unggah Dokumentasi</a>
            <a class="btn" href="{{ route('admin.laporan.index') }}">Laporan</a>
        </div>
    </div>

    <div class="a-metrics">
        <div class="a-m"><b>{{ $total }}</b><span>Total Kegiatan</span></div>
        <div class="a-m"><b>{{ $hariIniJumlah }}</b><span>Kegiatan Hari Ini</span></div>
        <div class="a-m"><b>{{ $bulanIni }}</b><span>Kegiatan Bulan Ini</span></div>
        <div class="a-m"><b>{{ $mendatang }}</b><span>Terjadwal Mendatang</span></div>
        <div class="a-m"><b>{{ $selesai }}</b><span>Kegiatan Selesai</span></div>
        <div class="a-m"><b>{{ $dokumentasi }}</b><span>Dokumentasi</span></div>
    </div>

    <div class="a-cols">
        <section class="card">
            <h2>Agenda Hari Ini</h2>
            @if ($agendaHariIni->isNotEmpty())
                <ul class="agl">@foreach ($agendaHariIni as $k)@include('partials.agenda', ['k' => $k, 'admin' => true])@endforeach</ul>
            @else
                @include('partials.kosong', ['judul' => 'Tidak ada agenda hari ini'])
            @endif
        </section>
        <section class="card">
            <h2>Agenda Terdekat</h2>
            @if ($agendaTerdekat->isNotEmpty())
                <ul class="agl">@foreach ($agendaTerdekat as $k)@include('partials.agenda', ['k' => $k, 'admin' => true])@endforeach</ul>
            @else
                @include('partials.kosong', ['judul' => 'Belum ada agenda mendatang'])
            @endif
        </section>
    </div>
@endsection
