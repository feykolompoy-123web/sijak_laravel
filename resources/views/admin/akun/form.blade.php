@extends('layouts.admin')

@php $baru = ! $u->exists; @endphp
@section('judul', $baru ? 'Tambah Admin' : 'Edit Akun')

@section('isi')
    <div class="a-head"><h1>{{ $baru ? 'Tambah Admin' : 'Edit Akun' }}</h1></div>

    <form class="card a-form" method="POST" novalidate data-sekali
          action="{{ $baru ? route('admin.akun.store') : route('admin.akun.update', $u) }}">
        @csrf
        @unless ($baru) @method('PUT') @endunless

        <label>Nama Lengkap
            <input name="name" value="{{ old('name', $u->name) }}" maxlength="100" class="@error('name') bad @enderror" required>
            @error('name')<p class="a-err">{{ $message }}</p>@enderror
        </label>
        <div class="a-grid">
            <label>Username
                <input name="username" value="{{ old('username', $u->username) }}" maxlength="50" autocomplete="off" class="@error('username') bad @enderror" required>
                @error('username')<p class="a-err">{{ $message }}</p>@enderror
            </label>
            <label>Email <small>(opsional)</small>
                <input type="email" name="email" value="{{ old('email', $u->email) }}" maxlength="150" class="@error('email') bad @enderror">
                @error('email')<p class="a-err">{{ $message }}</p>@enderror
            </label>
            <label>Password @unless ($baru)<small>(kosongkan jika tidak diubah)</small>@endunless
                <input type="password" name="password" autocomplete="new-password" class="@error('password') bad @enderror" @if ($baru) required @endif>
                <small>Minimal 8 karakter.</small>
                @error('password')<p class="a-err">{{ $message }}</p>@enderror
            </label>
            <label>Ulangi Password
                <input type="password" name="password_confirmation" autocomplete="new-password">
            </label>
        </div>
        <div class="a-bar">
            <button class="btn pri" type="submit">Simpan</button>
            <a class="btn" href="{{ route('admin.akun.index') }}">Batal</a>
        </div>
    </form>
@endsection
