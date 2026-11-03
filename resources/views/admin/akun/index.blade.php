@extends('layouts.admin')

@section('judul', 'Akun Admin')

@section('isi')
    <div class="a-head">
        <div><h1>Akun Admin</h1><p>Semua akun di sini memiliki akses penuh ke sistem.</p></div>
        <div class="a-actions"><a class="btn pri" href="{{ route('admin.akun.create') }}">+ Tambah Admin</a></div>
    </div>

    <div class="a-tw">
        <table class="a-table">
            <thead><tr><th>Nama</th><th>Username</th><th>Email</th><th>Dibuat</th><th></th></tr></thead>
            <tbody>
            @foreach ($daftar as $u)
                <tr>
                    <td><b>{{ $u->name }}</b> @if ($u->is(auth()->user()))<span class="badge r-admin">Anda</span>@endif</td>
                    <td>{{ $u->username }}</td>
                    <td>{{ $u->email ?: '-' }}</td>
                    <td>{{ $u->created_at->translatedFormat('d M Y') }}</td>
                    <td class="aksi">
                        <a class="btn sm" href="{{ route('admin.akun.edit', $u) }}">Edit</a>
                        @unless ($u->is(auth()->user()))
                            <form method="POST" action="{{ route('admin.akun.destroy', $u) }}" data-konfirmasi="Hapus akun &quot;{{ $u->username }}&quot;?">
                                @csrf @method('DELETE')<button class="btn sm red" type="submit">Hapus</button>
                            </form>
                        @endunless
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
