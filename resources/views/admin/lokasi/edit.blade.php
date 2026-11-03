@extends('layouts.admin')

@section('judul', 'Edit Lokasi')

@section('isi')
    <div class="a-head"><h1>Edit Lokasi</h1></div>
    <form class="card a-form" method="POST" action="{{ route('admin.lokasi.update', $item) }}" novalidate data-sekali>
        @csrf @method('PUT')
        <label>Nama Lokasi
            <input name="nama" value="{{ old('nama', $item->nama) }}" maxlength="150" class="@error('nama') bad @enderror" required autofocus>
            @error('nama')<p class="a-err">{{ $message }}</p>@enderror
        </label>
        <div class="a-bar">
            <button class="btn pri" type="submit">Simpan</button>
            <a class="btn" href="{{ route('admin.lokasi.index') }}">Batal</a>
        </div>
    </form>
@endsection
