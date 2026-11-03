
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title>Masuk - SIJAK Command Center Tomohon</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="login" data-page="login">
    <main class="lg2">

        {{-- PANEL KIRI --}}
        <section class="lt">
            <a class="pbrand" href="{{ route('beranda') }}">
                @include('partials.brand')
            </a>

         
<div class="ltc">
    <span class="eyebrow">
        DINAS KOMUNIKASI DAN INFORMATIKA KOTA TOMOHON
    </span>

    <i class="rule"></i>

    <h1>
        Sistem Informasi Jadwal dan<br>
        Agenda Kegiatan<br>
        <span>Command Center Tomohon</span>
    </h1>

    <p>
        Kelola jadwal, kalender agenda, dokumentasi, dan
        laporan kegiatan Command Center Kota Tomohon
        dalam satu sistem.
    </p>

    <div class="login-hero-actions">
        <a href="{{ route('jadwal') }}" class="login-hero-btn primary">
            <svg xmlns="http://www.w3.org/2000/svg"
                 width="20" height="20"
                 viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 aria-hidden="true">
                <rect x="3" y="4" width="18" height="17" rx="2"/>
                <path d="M16 2v4M8 2v4M3 10h18"/>
            </svg>

            Jelajahi Jadwal
            <span aria-hidden="true">→</span>
        </a>

        <a href="{{ route('kalender') }}" class="login-hero-btn secondary">
            <svg xmlns="http://www.w3.org/2000/svg"
                 width="20" height="20"
                 viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 aria-hidden="true">
                <rect x="3" y="4" width="18" height="17" rx="2"/>
                <path d="M16 2v4M8 2v4M3 10h18"/>
            </svg>

            Lihat Kalender
            <span aria-hidden="true">→</span>
        </a>
    </div>
    </div>
</section> {{-- PENUTUP PANEL KIRI --}}

    {{-- PANEL KANAN --}}
    <section class="lf">
            <form id="lf"
                  action="{{ route('login.proses') }}"
                  method="POST"
                  novalidate>

                @csrf

                <a class="bk" href="{{ route('beranda') }}">
                    ← Kembali ke beranda
                </a>

                <h2>Masuk ke SIJAK</h2>

                <p class="sub">
                    Gunakan akun admin yang diberikan oleh administrator.
                </p>

                @if (session('status'))
                    <div class="note" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="note fe-box" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- USERNAME --}}
                <div class="login-field">
                    <label for="u">Username atau Email</label>

                    <input
                        id="u"
                        name="username"
                        type="text"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        required
                        autofocus
                    >
                </div>

                {{-- PASSWORD --}}
                <div class="login-field">
                    <label for="p">Password</label>

                    <div class="pw">
                        <input
                            id="p"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            id="tg"
                            class="password-toggle"
                            aria-controls="p"
                            aria-label="Tampilkan password"
                            aria-pressed="false"
                        >
                            {{-- Ikon mata --}}
                            <svg
                                id="password-eye"
                                xmlns="http://www.w3.org/2000/svg"
                                width="22"
                                height="22"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- TOMBOL MASUK --}}
                <button id="lb" type="submit" class="btn pri w lgb">
                    <span>Masuk</span>
                </button>

            </form>
        </section>

    </main>

    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
