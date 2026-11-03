@extends('layouts.admin')

@section('judul', 'Laporan')

@section('isi')
    <div class="a-head a-noprint">
        <div><h1>Laporan Kegiatan</h1><p>Pilih periode dan filter, lalu cetak atau unduh sebagai CSV (dapat dibuka di Excel).</p></div>
        <div class="a-actions">
            <a class="btn" href="{{ route('admin.laporan.export', request()->query()) }}">Unduh CSV</a>
            <button class="btn pri" type="button" data-cetak>Cetak</button>
        </div>
    </div>

    <form class="a-filter a-noprint" method="GET" action="{{ route('admin.laporan.index') }}">
        <label>Dari tanggal<input type="date" name="dari" value="{{ $dari }}"></label>
        <label>Sampai tanggal<input type="date" name="sampai" value="{{ $sampai }}"></label>
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
        <label>Lokasi
            <select name="lokasi">
                <option value="">Semua</option>
                @foreach ($lokasi as $l)<option value="{{ $l->id }}" @selected(request('lokasi') == $l->id)>{{ $l->nama }}</option>@endforeach
            </select>
        </label>
        <button class="btn pri" type="submit">Tampilkan</button>
    </form>

    <div class="a-print">
        <h1>Laporan Jadwal dan Agenda Kegiatan Command Center</h1>
        <p><b>Dinas Komunikasi dan Informatika Kota Tomohon</b><br>
        Periode: {{ \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}</p>
    </div>

    <div class="a-sum">
        <span class="badge a-badge-ya">Total: {{ $daftar->count() }} kegiatan</span>
        @foreach ($ringkas as $status => $jumlah)
            <span class="badge {{ \App\Models\Kegiatan::BADGE[$status] }}">{{ $status }}: {{ $jumlah }}</span>
        @endforeach
    </div>

    <div class="a-tw">
        <table class="a-table">
            <thead><tr><th>No</th><th>Kode</th><th>Kegiatan</th><th>Tanggal</th><th>Jam</th><th>Lokasi</th><th>Kategori</th><th>Penanggung Jawab</th><th>Status</th></tr></thead>
            <tbody>
            @forelse ($daftar as $i => $k)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $k->kode }}</td>
                    <td><b>{{ $k->nama }}</b></td>
                    <td>{{ $k->tanggal->translatedFormat('d M Y') }}</td>
                    <td>{{ $k->jam }}</td>
                    <td>{{ $k->lokasi->nama }}</td>
                    <td>{{ $k->kategori->nama }}</td>
                    <td>{{ $k->penanggung_jawab }}</td>
                    <td><span class="badge {{ $k->badge }}">{{ $k->status }}</span></td>
                </tr>
            @empty
                <tr><td colspan="9" class="a-empty">Tidak ada kegiatan pada periode dan filter ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
