@extends('layouts.admin')

@php $baru = ! $k->exists; @endphp
@section('judul', $baru ? 'Tambah Kegiatan' : 'Edit Kegiatan')

@section('isi')
    <div class="a-head"><div><h1>{{ $baru ? 'Tambah Kegiatan' : 'Edit Kegiatan' }}</h1>@unless ($baru)<p>{{ $k->kode }}</p>@endunless</div></div>

    <form class="card a-form" method="POST" novalidate data-sekali
          action="{{ $baru ? route('admin.kegiatan.store') : route('admin.kegiatan.update', $k) }}">
        @csrf
        @unless ($baru) @method('PUT') @endunless

        <label class="a-full">Nama Kegiatan
            <input name="nama" value="{{ old('nama', $k->nama) }}" maxlength="150" class="@error('nama') bad @enderror" required>
            @error('nama')<p class="a-err">{{ $message }}</p>@enderror
        </label>

        <div class="a-grid">
            <label>Tanggal
                <input type="date" name="tanggal" value="{{ old('tanggal', $baru ? request('tanggal', optional($k->tanggal)->format('Y-m-d')) : $k->tanggal->format('Y-m-d')) }}" class="@error('tanggal') bad @enderror" required>
                @error('tanggal')<p class="a-err">{{ $message }}</p>@enderror
            </label>
            <label>Status
                <select name="status" class="@error('status') bad @enderror">
                    @foreach (\App\Models\Kegiatan::STATUS as $s)<option @selected(old('status', $k->status) === $s)>{{ $s }}</option>@endforeach
                </select>
                @error('status')<p class="a-err">{{ $message }}</p>@enderror
            </label>
            <label>Jam Mulai
                <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $k->mulai) }}" class="@error('jam_mulai') bad @enderror" required>
                @error('jam_mulai')<p class="a-err">{{ $message }}</p>@enderror
            </label>
            <label>Jam Selesai
                <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $k->selesai) }}" class="@error('jam_selesai') bad @enderror" required>
                @error('jam_selesai')<p class="a-err">{{ $message }}</p>@enderror
            </label>
            <label>Lokasi
                <select name="lokasi_id" class="@error('lokasi_id') bad @enderror" required>
                    <option value="">-- Pilih lokasi --</option>
                    @foreach ($lokasi as $l)<option value="{{ $l->id }}" @selected(old('lokasi_id', $k->lokasi_id) == $l->id)>{{ $l->nama }}</option>@endforeach
                </select>
                @error('lokasi_id')<p class="a-err">{{ $message }}</p>@enderror
            </label>
            <label>Kategori
                <select name="kategori_id" class="@error('kategori_id') bad @enderror" required>
                    <option value="">-- Pilih kategori --</option>
                    @foreach ($kategori as $c)<option value="{{ $c->id }}" @selected(old('kategori_id', $k->kategori_id) == $c->id)>{{ $c->nama }}</option>@endforeach
                </select>
                @error('kategori_id')<p class="a-err">{{ $message }}</p>@enderror
            </label>
        </div>

        <label>Penanggung Jawab
            <input name="penanggung_jawab" value="{{ old('penanggung_jawab', $k->penanggung_jawab) }}" maxlength="100" class="@error('penanggung_jawab') bad @enderror" required>
            @error('penanggung_jawab')<p class="a-err">{{ $message }}</p>@enderror
        </label>

        <label>Keterangan <small>(opsional)</small>
            <textarea name="keterangan" maxlength="2000">{{ old('keterangan', $k->keterangan) }}</textarea>
            @error('keterangan')<p class="a-err">{{ $message }}</p>@enderror
        </label>

        <label class="a-chk">
            <input type="hidden" name="dipublikasikan" value="0">
            <input type="checkbox" name="dipublikasikan" value="1" @checked(old('dipublikasikan', $k->dipublikasikan))>
            Tampilkan di halaman publik
        </label>

        <div class="a-bar">
            <button class="btn pri" type="submit">Simpan</button>
            <a class="btn" href="{{ $baru ? route('admin.kegiatan.index') : route('admin.kegiatan.show', $k) }}">Batal</a>
        </div>
    </form>
@endsection
