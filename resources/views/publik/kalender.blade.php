@extends('layouts.publik')

@section('judul', 'Kalender Agenda')
@section('halaman', 'pkal')

@section('isi')
    <div class="sh"><h1>Kalender Agenda</h1><p>Pilih tanggal untuk melihat agenda yang dipublikasikan.</p></div>

    <div class="two x">
        <div class="card" id="cal">
            @include('partials.kalender', ['rute' => 'kalender'])
        </div>
        <div class="card" id="dl">
            <h3>{{ \Carbon\Carbon::parse($tgl)->translatedFormat('l, d F Y') }}</h3>
            @forelse ($daftar as $k)
                <div class="ev {{ $k->badge }}">
                    <a class="et" href="{{ route('kegiatan.detail', $k) }}"><b>{{ $k->nama }}</b></a>
                    <small>{{ $k->jam }} · {{ $k->lokasi->nama }}</small>
                    <span class="badge {{ $k->badge }}">{{ $k->status }}</span>
                </div>
            @empty
                @include('partials.kosong', ['judul' => 'Tidak ada agenda pada tanggal ini'])
            @endforelse
        </div>
    </div>
@endsection
