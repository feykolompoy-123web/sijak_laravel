<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('judul', 'Dashboard') - Admin SIJAK Command Center</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body data-page="admin">
@php
    $menu = [
        ['Dashboard', route('admin.dashboard'), 'admin.dashboard'],
        ['Kegiatan', route('admin.kegiatan.index'), 'admin.kegiatan.*'],
        ['Kalender', route('admin.kalender'), 'admin.kalender'],
        ['Kategori', route('admin.kategori.index'), 'admin.kategori.*'],
        ['Lokasi', route('admin.lokasi.index'), 'admin.lokasi.*'],
        ['Dokumentasi', route('admin.dokumentasi.index'), 'admin.dokumentasi.*'],
        ['Laporan', route('admin.laporan.index'), 'admin.laporan.*'],
        ['Akun Admin', route('admin.akun.index'), 'admin.akun.*'],
    ];
@endphp
    <a class="skip" href="#main">Lewati ke konten</a>

    <header class="a-top">
        <div class="a-wrap">
            <a class="pbrand" href="{{ route('admin.dashboard') }}">@include('partials.brand')</a>
            <button class="a-ham" type="button" aria-label="Buka menu" aria-expanded="false" data-nav>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <nav class="a-nav" aria-label="Menu admin">
                @foreach ($menu as [$nama, $url, $pola])
                    <a href="{{ $url }}" class="{{ request()->routeIs($pola) ? 'on' : '' }}" @if (request()->routeIs($pola)) aria-current="page" @endif>{{ $nama }}</a>
                @endforeach
            </nav>
            <div class="a-user">
                <span>{{ auth()->user()->name }}</span>
                <a href="{{ route('beranda') }}" target="_blank" rel="noopener">Lihat Situs</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn sm gh" type="submit">Keluar</button></form>
            </div>
        </div>
    </header>

    <main id="main" class="a-main">
        @yield('isi')
    </main>

    @include('partials.flash')
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
