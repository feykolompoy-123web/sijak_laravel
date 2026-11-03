@extends('layouts.publik')

@section('judul', 'Dokumentasi')
@section('halaman', 'pdok')

@section('isi')
    <div class="sh"><h1>Dokumentasi Kegiatan</h1><p>Foto dan dokumen dari kegiatan yang dipublikasikan.</p></div>

    @if ($daftar->isNotEmpty())
        @include('partials.galeri', ['daftar' => $daftar])
        {{ $daftar->links() }}
    @else
        @include('partials.kosong', ['judul' => 'Belum ada dokumentasi yang dipublikasikan'])
    @endif
@endsection
