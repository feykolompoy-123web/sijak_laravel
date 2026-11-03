@extends('layouts.admin')

@section('judul', 'Dokumentasi')

@section('isi')
    <div class="a-head">
        <div><h1>Dokumentasi Kegiatan</h1><p>Foto (JPG, PNG, WEBP) dan dokumen (PDF, DOC, DOCX), maksimal 5 MB per berkas.</p></div>
        <div class="a-actions"><a class="btn pri" href="{{ route('admin.dokumentasi.create') }}">+ Unggah Dokumentasi</a></div>
    </div>

    <form class="a-filter" method="GET" action="{{ route('admin.dokumentasi.index') }}">
        <label>Kegiatan
            <select name="kegiatan">
                <option value="">Semua kegiatan</option>
                @foreach ($kegiatan as $k)<option value="{{ $k->id }}" @selected(request('kegiatan') == $k->id)>{{ $k->kode }} - {{ $k->nama }}</option>@endforeach
            </select>
        </label>
        <label>Jenis
            <select name="tipe">
                <option value="">Semua</option>
                <option @selected(request('tipe') === 'Foto')>Foto</option>
                <option @selected(request('tipe') === 'Dokumen')>Dokumen</option>
            </select>
        </label>
        <button class="btn pri" type="submit">Terapkan</button>
        <a class="btn" href="{{ route('admin.dokumentasi.index') }}">Reset</a>
    </form>

    @if ($daftar->isNotEmpty())
        @include('admin.dokumentasi.kartu', ['daftar' => $daftar])
        {{ $daftar->links() }}
    @else
        <div class="card">@include('partials.kosong', ['judul' => 'Belum ada dokumentasi'])</div>
    @endif
@endsection
