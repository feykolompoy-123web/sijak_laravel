@extends('layouts.publik')

@section('judul', 'Beranda')
@section('halaman', 'home')

@section('hero')
<section
    class="hero"
    style="--hero-img: url('{{ asset('assets/images/Tomohon Command Center Stage (1).png') }}')"
    aria-label="Command Center Kota Tomohon"
>
    <div class="pw hg">

        <div class="ht">
            <span class="eyebrow">
                Dinas Komunikasi dan Informatika Kota Tomohon
            </span>

            <h1>
                Sistem Informasi Jadwal dan Agenda Kegiatan
                <em>Command Center Tomohon</em>
            </h1>

            <p>
                Pusat informasi jadwal, agenda, dan dokumentasi kegiatan
                yang terintegrasi untuk mendukung pengelolaan informasi
                Command Center Kota Tomohon.
            </p>

            <div class="acts2">
                <a class="btn cu" href="{{ route('jadwal') }}">
                    Jelajahi Jadwal
                </a>

                <a class="btn ol" href="{{ route('kalender') }}">
                    Lihat Kalender
                </a>
            </div>

        </div>

        <aside class="hcard" aria-label="Agenda terdekat">
            <div class="ch2">
                <h2>Agenda Terdekat</h2>
                <a href="{{ route('kalender') }}">Lihat Semua →</a>
            </div>

            @if ($terdekat->isNotEmpty())
                <ul class="agl">
                    @foreach ($terdekat as $k)
                        @include('partials.agenda', ['k' => $k])
                    @endforeach
                </ul>
            @else
                @include('partials.kosong', [
                    'judul' => 'Belum ada agenda yang dipublikasikan'
                ])
            @endif
        </aside>

    </div>
</section>
@endsection

@section('isi')
<div class="cols">
    <section>
        <div class="ch2">
            <h2>Agenda Hari Ini</h2>
            <a href="{{ route('kalender') }}">Lihat Semua →</a>
        </div>

        @if ($hariIni->isNotEmpty())
            <ul class="agl">
                @foreach ($hariIni as $k)
                    @include('partials.agenda', ['k' => $k])
                @endforeach
            </ul>
        @else
            @include('partials.kosong', [
                'judul' => 'Tidak ada agenda hari ini'
            ])
        @endif
    </section>

    <section>
        <div class="ch2">
            <h2>Kegiatan Mendatang</h2>
            <a href="{{ route('jadwal') }}">Lihat Semua →</a>
        </div>

        @if ($mendatang->isNotEmpty())
            <ul class="agl">
                @foreach ($mendatang as $k)
                    @include('partials.agenda', ['k' => $k])
                @endforeach
            </ul>
        @else
            @include('partials.kosong', [
                'judul' => 'Belum ada kegiatan mendatang'
            ])
        @endif
    </section>
</div>

<section class="blk2">
    <div class="ch2">
        <h2>Informasi Jadwal Terbaru</h2>
        <a href="{{ route('jadwal') }}">Lihat semua jadwal →</a>
    </div>

    @if ($terbaru->isNotEmpty())
        <ul class="agl">
            @foreach ($terbaru as $k)
                @include('partials.agenda', ['k' => $k])
            @endforeach
        </ul>
    @else
        @include('partials.kosong', [
            'judul' => 'Belum ada agenda yang dipublikasikan'
        ])
    @endif
</section>
@endsection