@extends('layouts.admin')

@section('judul', 'Edit Kategori')

@section('isi')
    <div class="a-head"><h1>Edit Kategori</h1></div>
    <form class="card a-form" method="POST" action="{{ route('admin.kategori.update', $item) }}" novalidate data-sekali>
        @csrf @method('PUT')
        <label>Nama Kategori
            <input name="nama" value="{{ old('nama', $item->nama) }}" maxlength="100" class="@error('nama') bad @enderror" required autofocus>
            @error('nama')<p class="a-err">{{ $message }}</p>@enderror
        </label>
        <div class="a-bar">
            <button class="btn pri" type="submit">Simpan</button>
            <a class="btn" href="{{ route('admin.kategori.index') }}">Batal</a>
        </div>
    </form>
@endsection
