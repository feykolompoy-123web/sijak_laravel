@extends('layouts.admin')

@section('judul', 'Kegiatan')

@section('isi')
    <div class="a-head">
        <div><h1>Jadwal Kegiatan</h1><p>Kelola seluruh jadwal dan agenda Command Center.</p></div>
        <div class="a-actions"><a class="btn pri" href="{{ route('admin.kegiatan.create') }}">+ Tambah Kegiatan</a></div>
    </div>

    <form class="a-filter" method="GET" action="{{ route('admin.kegiatan.index') }}">
        <label>Cari<input name="q" value="{{ request('q') }}" placeholder="Nama, kode, atau penanggung jawab"></label>
        <label>Status
            <select name="status">
                <option value="">Semua</option>
                @foreach (\App\Models\Kegiatan::STATUS as $s)<option @selected(request('status') === $s)>{{ $s }}</option>@endforeach
            </select>
        </label>
        <label>Kategori
            <select name="kategori">
                <option value="">Semua</option>
                @foreach ($kategori as $c)<option value="{{ $c->id }}" @selected(request('kategori') == $c->id)>{{ $c->nama }}</option>@endforeach
            </select>
        </label>
        <label>Tanggal<input type="date" name="tanggal" value="{{ request('tanggal') }}"></label>
        <button class="btn pri" type="submit">Terapkan</button>
        <a class="btn" href="{{ route('admin.kegiatan.index') }}">Reset</a>
    </form>

    <div class="a-tw">
        <table class="a-table">
            <thead><tr><th>Kode</th><th>Kegiatan</th><th>Tanggal &amp; Jam</th><th>Lokasi</th><th>Penanggung Jawab</th><th>Status</th><th>Publik</th><th></th></tr></thead>
            <tbody>
            @forelse ($daftar as $k)
                <tr>
                    <td>{{ $k->kode }}</td>
                    <td><a href="{{ route('admin.kegiatan.show', $k) }}"><b>{{ $k->nama }}</b></a><small>{{ $k->kategori->nama }}</small></td>
                    <td>{{ $k->tanggal->translatedFormat('d M Y') }}<small>{{ $k->jam }}</small></td>
                    <td>{{ $k->lokasi->nama }}</td>
                    <td>{{ $k->penanggung_jawab }}</td>
                    <td><span class="badge {{ $k->badge }}">{{ $k->status }}</span></td>
                    <td><span class="badge {{ $k->dipublikasikan ? 'a-badge-ya' : 'a-badge-tdk' }}">{{ $k->dipublikasikan ? 'Ya' : 'Tidak' }}</span></td>
                    <td class="aksi">
                        <a class="btn sm" href="{{ route('admin.kegiatan.show', $k) }}">Detail</a>
                        <a class="btn sm" href="{{ route('admin.kegiatan.edit', $k) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.kegiatan.destroy', $k) }}" data-konfirmasi="Hapus kegiatan &quot;{{ $k->nama }}&quot; beserta dokumentasinya? Tindakan ini tidak dapat dibatalkan.">
                            @csrf @method('DELETE')<button class="btn sm red" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="a-empty">Belum ada kegiatan yang sesuai.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $daftar->links() }}
@endsection
