@extends('layouts.admin')

@section('judul', 'Kategori')

@section('isi')
    <div class="a-head"><div><h1>Kategori</h1><p>Daftar kategori yang dipakai pada formulir kegiatan.</p></div></div>

    <form class="a-filter" method="POST" action="{{ route('admin.kategori.store') }}" novalidate>
        @csrf
        <label>Tambah Kategori baru
            <input name="nama" value="{{ old('nama') }}" maxlength="100" placeholder="Contoh: Rapat Koordinasi" class="@error('nama') bad @enderror" required>
            @error('nama')<p class="a-err">{{ $message }}</p>@enderror
        </label>
        <button class="btn pri" type="submit">+ Tambah</button>
    </form>

    <div class="a-tw">
        <table class="a-table" style="min-width:480px">
            <thead><tr><th>Nama</th><th>Dipakai oleh</th><th></th></tr></thead>
            <tbody>
            @forelse ($daftar as $i)
                <tr>
                    <td><b>{{ $i->nama }}</b></td>
                    <td>{{ $i->kegiatan_count }} kegiatan</td>
                    <td class="aksi">
                        <a class="btn sm" href="{{ route('admin.kategori.edit', $i) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.kategori.destroy', $i) }}" data-konfirmasi="Hapus kategori &quot;{{ $i->nama }}&quot;?">
                            @csrf @method('DELETE')<button class="btn sm red" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="a-empty">Belum ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
