/* SIJAK Command Center Tomohon - interaksi ringan. Semua data dikelola server (Laravel + MySQL). */
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    
    // Tampilkan / sembunyikan password pada halaman login.
    var tg = document.getElementById('tg');
    var pw = document.getElementById('p');
    var eye = document.getElementById('password-eye');

    if (tg && pw && eye) {
        tg.addEventListener('click', function () {
            var lihat = pw.type === 'password';

            pw.type = lihat ? 'text' : 'password';

            tg.setAttribute(
                'aria-label',
                lihat ? 'Sembunyikan password' : 'Tampilkan password'
            );

            tg.setAttribute(
                'aria-pressed',
                lihat ? 'true' : 'false'
            );

            if (lihat) {
                // Ikon mata dicoret: password sedang terlihat.
                eye.innerHTML = `
                    <path d="M3 3l18 18"/>
                    <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                    <path d="M9.9 5.2A10.7 10.7 0 0 1 12 5"/>
                    <path d="M2 12s3.6 7 10 7a10 10 0 0 0 3-.5"/>
                    <path d="M6.6 6.6C3.6 8.5 2 12 2 12s1.1 2.2 3.3 4"/>
                `;
            } else {
                // Ikon mata normal: password tersembunyi.
                eye.innerHTML = `
                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                    <circle cx="12" cy="12" r="3"/>
                `;
            }
        });
    }

    // Konfirmasi sebelum menghapus: <form data-konfirmasi="Pesan...">
    document.querySelectorAll('form[data-konfirmasi]').forEach(function (f) {
        f.addEventListener('submit', function (e) {
            if (!window.confirm(f.getAttribute('data-konfirmasi'))) { e.preventDefault(); }
        });
    });

    // Cegah klik ganda saat formulir dikirim.
    document.querySelectorAll('form').forEach(function (f) {
        f.addEventListener('submit', function (e) {
            if (e.defaultPrevented) { return; }
            var b = f.querySelector('button[type="submit"], button:not([type])');
            if (b && f.hasAttribute('data-sekali')) { setTimeout(function () { b.disabled = true; }, 0); }
        });
    });

    // Pesan sukses/galat hilang otomatis.
    var toasts = document.getElementById('toasts');
    if (toasts) { setTimeout(function () { toasts.remove(); }, 4500); }

    // Pratinjau berkas yang dipilih (formulir dokumentasi).
    var berkas = document.getElementById('berkas'), pv = document.getElementById('pratinjau');
    if (berkas && pv) {
        berkas.addEventListener('change', function () {
            var f = berkas.files[0];
            pv.innerHTML = '';
            if (!f) { return; }
            if (f.type.indexOf('image/') === 0) {
                var img = document.createElement('img');
                img.alt = 'Pratinjau';
                img.src = URL.createObjectURL(f);
                pv.appendChild(img);
            } else {
                pv.textContent = f.name;
            }
        });
    }

    // Tombol cetak: <button data-cetak>
    document.querySelectorAll('[data-cetak]').forEach(function (b) {
        b.addEventListener('click', function () { window.print(); });
    });
});
 
    // Efek sticky navbar SIJAK saat halaman di-scroll.
    var navbar = document.querySelector('[data-sticky-nav]');

    if (navbar) {
        var updateNavbar = function () {
            if (window.scrollY > 30) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        };

        window.addEventListener('scroll', updateNavbar, {
            passive: true
        });

        // Periksa posisi scroll saat halaman pertama kali dibuka.
        updateNavbar();

        // Pengatur ukuran teks pada Hero
var hero = document.querySelector('.hero');
var tombolKecil = document.getElementById('font-down');
var tombolNormal = document.getElementById('font-reset');
var tombolBesar = document.getElementById('font-up');

if (hero && tombolKecil && tombolNormal && tombolBesar) {
    var ukuranTeks = 17;

    function aturUkuranTeks() {
        var judul = hero.querySelector('.ht h1');
        var paragraf = hero.querySelector('.ht p');

        if (judul) {
            judul.style.fontSize = (ukuranTeks * 2.2) + 'px';
        }

        if (paragraf) {
            paragraf.style.fontSize = ukuranTeks + 'px';
        }
    }

    tombolKecil.addEventListener('click', function () {
        ukuranTeks = Math.max(13, ukuranTeks - 1);
        aturUkuranTeks();
    });

    tombolNormal.addEventListener('click', function () {
        ukuranTeks = 17;
        aturUkuranTeks();
    });

    tombolBesar.addEventListener('click', function () {
        ukuranTeks = Math.min(24, ukuranTeks + 1);
        aturUkuranTeks();
    });
}
    }
