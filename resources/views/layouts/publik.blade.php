<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('judul', 'Beranda') - SIJAK Command Center Tomohon</title>
    <meta name="description" content="Sistem Informasi Jadwal dan Agenda Kegiatan Command Center Tomohon - Dinas Komunikasi dan Informatika Kota Tomohon.">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body data-page="@yield('halaman', 'home')">
@php
    $menu = [
        ['Beranda', route('beranda'), request()->routeIs('beranda')],
        ['Jadwal Kegiatan', route('jadwal'), request()->routeIs('jadwal', 'kegiatan.detail')],
        ['Kalender Agenda', route('kalender'), request()->routeIs('kalender')],
        ['Dokumentasi', route('dokumentasi'), request()->routeIs('dokumentasi')],
    ];
@endphp
    <a class="skip" href="#main">Lewati ke konten</a>

    <header class="ph0">
        <div class="pw hd0">
            <a class="pbrand" href="{{ route('beranda') }}">@include('partials.brand')</a>
            <button class="ham" type="button" aria-label="Buka menu" aria-expanded="false" data-nav>
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <nav class="pnav" aria-label="Menu publik">
                @foreach ($menu as [$nama, $url, $aktif])
                    <a href="{{ $url }}" class="{{ $aktif ? 'on' : '' }}" @if ($aktif) aria-current="page" @endif>{{ $nama }}</a>
                @endforeach
                <a class="btn pri" href="{{ auth()->check() ? route('admin.dashboard') : route('login') }}">{{ auth()->check() ? 'Dashboard Admin' : 'Login Admin' }}</a>
            </nav>
        </div>
    </header>

    <main id="main">
        @yield('hero')
        <div class="pw sec">
            @yield('isi')
        </div>
    </main>

    <footer class="pf">
        <div class="pw fg2">
            <div>
                <a class="pbrand" href="{{ route('beranda') }}">@include('partials.brand')</a>
                <p>Sistem Informasi Jadwal dan Agenda Kegiatan Command Center Tomohon.</p>
                <p><b>Dinas Komunikasi dan Informatika Kota Tomohon</b></p>
            </div>
            <div>
                <h4>Informasi</h4>
                @foreach (array_slice($menu, 1) as [$nama, $url])<a href="{{ $url }}">{{ $nama }}</a>@endforeach
            </div>
            <div>
                <h4>Pengelola</h4>
                <a href="{{ route('login') }}">Admin</a>
            </div>
        </div>
        <div class="pw cr">© {{ date('Y') }} Dinas Komunikasi dan Informatika Kota Tomohon. Seluruh hak dilindungi.</div>
    </footer>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
    (function () {
        var header = document.querySelector('.ph0');
        if (!header) return;
        function cek() { header.classList.toggle('scrolled', window.scrollY > 40); }
        cek();
        window.addEventListener('scroll', cek, { passive: true });
    })();
    </script>
</body>
</html>
