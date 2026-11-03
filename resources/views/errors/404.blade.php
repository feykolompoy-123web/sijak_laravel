@extends('layouts.publik')

@section('judul', 'Halaman tidak ditemukan')

@section('isi')
    <div class="sh"><h1>Halaman tidak ditemukan</h1><p>Alamat yang Anda buka tidak tersedia atau kegiatan tidak dipublikasikan.</p></div>
    <p><a class="btn pri" href="{{ route('beranda') }}">Kembali ke Beranda</a></p>
@endsection
