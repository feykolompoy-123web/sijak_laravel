{{-- Satu baris agenda. Parameter: $k (Kegiatan), $admin (opsional) --}}
@php
    $admin = $admin ?? false;
    $url = $admin ? route('admin.kegiatan.show', $k) : route('kegiatan.detail', $k);
@endphp
<li class="ag">
    <div class="db{{ $k->tanggal->isToday() ? ' td' : '' }}"><b>{{ $k->tanggal->day }}</b><small>{{ $k->tanggal->translatedFormat('M') }}</small></div>
    <div class="ab">
        <a class="an" href="{{ $url }}">{{ $k->nama }}</a>
        <span>{{ $k->jam }} · {{ $k->lokasi->nama }}@if ($admin) · {{ $k->penanggung_jawab }}@endif</span>
        <small>{{ $k->kategori->nama }}</small>
    </div>
    <span class="badge {{ $k->badge }}">{{ $k->status }}</span>
    <a class="btn sm" href="{{ $url }}">{{ $admin ? 'Detail' : 'Lihat Detail' }}</a>
</li>
