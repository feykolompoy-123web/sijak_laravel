@extends('layouts.admin')

@section('judul', 'Kalender')

@section('isi')
    <div class="a-head">
        <div><h1>Kalender Kegiatan</h1><p>Semua kegiatan, termasuk yang belum dipublikasikan.</p></div>
        <div class="a-actions"><a class="btn pri" href="{{ route('admin.kegiatan.create', ['tanggal' => $tgl]) }}">+ Tambah Kegiatan</a></div>
    </div>

    <div class="two x">
        <div class="card" id="cal">@include('partials.kalender', ['rute' => 'admin.kalender'])</div>
        <div class="card" id="dl">
            <h3>{{ \Carbon\Carbon::parse($tgl)->translatedFormat('l, d F Y') }}</h3>
            @forelse ($daftar as $k)
                <div class="ev {{ $k->badge }}">
                    <a class="et" href="{{ route('admin.kegiatan.show', $k) }}"><b>{{ $k->nama }}</b></a>
                    <small>{{ $k->jam }} · {{ $k->lokasi->nama }}</small>
                    <span class="badge {{ $k->badge }}">{{ $k->status }}</span>
                    @unless ($k->dipublikasikan)<small>Tidak dipublikasikan</small>@endunless
                </div>
            @empty
                @include('partials.kosong', ['judul' => 'Tidak ada kegiatan pada tanggal ini'])
            @endforelse
        </div>
    </div>
@endsection
