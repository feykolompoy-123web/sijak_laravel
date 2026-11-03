{{-- Kalender bulanan. Parameter: $kal, $tgl, $rute (nama route kalender) --}}
@php
    $hariIni = now()->toDateString();
    $tujuan = fn ($ym) => $ym === now()->format('Y-m') ? $hariIni : $ym.'-01';
@endphp
<div class="ch">
    <b>{{ $kal['judul'] }}</b>
    <div class="cn">
        <a class="btn sm" href="{{ route($rute, ['bulan' => $kal['sebelumnya'], 'tgl' => $tujuan($kal['sebelumnya'])]) }}" aria-label="Bulan sebelumnya">‹</a>
        <a class="btn sm" href="{{ route($rute, ['tgl' => $hariIni]) }}">Hari ini</a>
        <a class="btn sm" href="{{ route($rute, ['bulan' => $kal['berikutnya'], 'tgl' => $tujuan($kal['berikutnya'])]) }}" aria-label="Bulan berikutnya">›</a>
    </div>
</div>
<div class="cg">
    @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $h)<i class="hd">{{ $h }}</i>@endforeach
    @for ($i = 0; $i < $kal['kosong']; $i++)<i></i>@endfor
    @for ($d = 1; $d <= $kal['hari']; $d++)
        @php
            $s = $kal['awal']->copy()->day($d)->toDateString();
            $c = $kal['jumlah'][$s] ?? 0;
        @endphp
        <a class="dy{{ $s === $hariIni ? ' td' : '' }}{{ $s === $tgl ? ' sl' : '' }}"
           href="{{ route($rute, ['bulan' => $kal['awal']->format('Y-m'), 'tgl' => $s]) }}"
           aria-label="{{ $d }} {{ $kal['judul'] }}{{ $c ? ', '.$c.' kegiatan' : '' }}">{{ $d }}@if ($c)<u>{{ $c }}</u>@endif</a>
    @endfor
</div>
