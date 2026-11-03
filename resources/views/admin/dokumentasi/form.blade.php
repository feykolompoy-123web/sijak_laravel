@extends('layouts.admin')

@php $baru = ! $d->exists; @endphp
@section('judul', $baru ? 'Unggah Dokumentasi' : 'Edit Dokumentasi')

@section('isi')
    <div class="a-head"><h1>{{ $baru ? 'Unggah Dokumentasi' : 'Edit Dokumentasi' }}</h1></div>

    <form class="card a-form" method="POST" enctype="multipart/form-data" novalidate data-sekali
          action="{{ $baru ? route('admin.dokumentasi.store') : route('admin.dokumentasi.update', $d) }}">
        @csrf
        @unless ($baru) @method('PUT') @endunless

        <label>Judul
            <input name="judul" value="{{ old('judul', $d->judul) }}" maxlength="150" class="@error('judul') bad @enderror" required>
            @error('judul')<p class="a-err">{{ $message }}</p>@enderror
        </label>

        <label>Kegiatan terkait <small>(opsional)</small>
            <select name="kegiatan_id" class="@error('kegiatan_id') bad @enderror">
                <option value="">-- Tidak terkait kegiatan --</option>
                @foreach ($kegiatan as $k)<option value="{{ $k->id }}" @selected(old('kegiatan_id', $d->kegiatan_id) == $k->id)>{{ $k->kode }} - {{ $k->nama }}</option>@endforeach
            </select>
            @error('kegiatan_id')<p class="a-err">{{ $message }}</p>@enderror
        </label>

        <label>Berkas @unless ($baru)<small>(kosongkan jika tidak ingin mengganti)</small>@endunless
            <input type="file" id="berkas" name="berkas" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx" @if ($baru) required @endif class="@error('berkas') bad @enderror">
            <small>Format: JPG, PNG, WEBP, PDF, DOC, DOCX. Maksimal 5 MB.</small>
            @error('berkas')<p class="a-err">{{ $message }}</p>@enderror
            <div id="pratinjau">
                @unless ($baru)
                    @if ($d->is_foto)<img src="{{ $d->url }}" alt="Berkas saat ini">@else Berkas saat ini: {{ $d->nama_asli }}@endif
                @endunless
            </div>
        </label>

        <label class="a-chk">
            <input type="hidden" name="dipublikasikan" value="0">
            <input type="checkbox" name="dipublikasikan" value="1" @checked(old('dipublikasikan', $d->dipublikasikan))>
            Tampilkan di halaman publik
        </label>

        <div class="a-bar">
            <button class="btn pri" type="submit">Simpan</button>
            <a class="btn" href="{{ route('admin.dokumentasi.index') }}">Batal</a>
        </div>
    </form>
@endsection
