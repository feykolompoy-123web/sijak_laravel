<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PublikController;
use Illuminate\Support\Facades\Route;

/* ---------- Halaman publik ---------- */
Route::controller(PublikController::class)->group(function () {
    Route::get('/', 'beranda')->name('beranda');
    Route::get('/jadwal', 'jadwal')->name('jadwal');
    Route::get('/kalender', 'kalender')->name('kalender');
    Route::get('/kegiatan/{kegiatan}', 'detail')->whereNumber('kegiatan')->name('kegiatan.detail');
    Route::get('/dokumentasi', 'dokumentasi')->name('dokumentasi');
});

/* ---------- Login admin ---------- */
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'form'])->name('login');
    Route::post('/login', [LoginController::class, 'proses'])->middleware('throttle:10,1')->name('login.proses');
});
Route::post('/logout', [LoginController::class, 'keluar'])->middleware('auth')->name('logout');

/* ---------- Area admin (wajib login) ---------- */
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('kegiatan', Admin\KegiatanController::class);
    Route::get('kalender', [Admin\KalenderController::class, 'index'])->name('kalender');

    Route::resource('kategori', Admin\KategoriController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);
    Route::resource('lokasi', Admin\LokasiController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);
    Route::resource('dokumentasi', Admin\DokumentasiController::class)->except('show');

    Route::get('laporan', [Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [Admin\LaporanController::class, 'export'])->name('laporan.export');

    Route::resource('akun', Admin\AkunController::class)->except('show');
});
